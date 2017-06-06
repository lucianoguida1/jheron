<?php

	/**
	 * @name: Auth
	 * @description: Módulo responsável por gerenciar o processo de autenticação de usuários
	 * @author: Bruno C. Santos
	 * @version: 1.0
	 */

	class Auth{

		/**
		 * Injeção do Helper de E-mail
		 * @var object
		 */
		protected $email;

		/**
		 * Mensagens de erro padronizadas para o AlertHelper
		 * @var array
		 */
		public $errors_msg=array(
			'',
			array(
				'danger',
				'Ops! Conta em uso!',
				'Esta conta já está registrada e sendo utilizada no momento!'
			),
			array(
				'warning',
				'Ops! Verifique com cuidado os dados!',
				'Suas tentativas estão acabando, você só tem %%s%% tentativa(s)! Após exceder esse número seu acesso só será liberado através da aprovação do administrador!'
			),
			array('info',
				'Ops! Dados incorretos!',
				'Não foi possível realizar a autenticação, confira seus dados!'
			),
			array('danger',
				'Ops! Usuário bloqueado!',
				'Não foi possível realizar a autenticação, pois este usuário encontra-se bloqueado no sistema, contate o administrador para liberação!'
			),
			array('danger',
				'Ops! Dados incorretos!',
				'Não foi possível realizar a autenticação, confira seus dados!'
			)
		);

		/**
		 * Atributo que armazenará os erros durante o processo
		 * @var array
		 */
		public $errors=array();
		
		/**
		 * Mensagem que será enviada quando um usuário for bloqueado
		 * TAGS PERMITIDAS:
		 * - %%full_name%% = Atribui o nome completo do usuário
		 * @var string
		 */
		public $msg_user_locked='Olá %%full_name%%, <br /> Seu acesso foi bloqueado pelo excesso de tentativas, contate o administrador para liberação. Lembre-se de que esse recurso é para sua segurança, caso necessário solicite um novo acesso para evitar transtornos futuros.';

		/**
		 * Método construtor
		 */
		public function __construct(){
			//Instância dos objetos injetados
			$this->email=new EmailHelper;
		}

		/**
		 * Método responsável pela autenticação
		 * @param  string $username Nome de usuário para a autenticação
		 * @param  string $password Senha do usuário para a autenticação
		 * @return boolean          Retorna o status da autenticação
		 */
		public function login($username, $password) {
			$user_data=User::find_by_username($username);
			if(count($user_data) == 1 && !empty($user_data)){
				
				$password=Tools::hashHX($password,$user_data->salt);
				$password=$password['password'];

				if($user_data->status == 2)
					return $this->setError(4);
				else{
					if(LoginAttempt::CheckTentativa($user_data->id) == false) { 
						if($user_data->password == $password){
							LoginAttempt::LimparTentativas($user_data->id);
							$user_data->update_attributes(array('status' => 1));
			            	$_SESSION['user_id'] = preg_replace("/[^0-9]+/", "", $user_data->id); 
			            	$_SESSION['username'] = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username);
			            	$_SESSION['login_string'] = hash('sha512', $_SESSION['username'].$_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']);
			            	return true;
			          	}else{
			          		LoginAttempt::ArmazenarTentativa($user_data->id);
			          		if(LoginAttempt::CountTentativa($user_data->id) > 2){
			          			$this->errors_msg[2][2]=DataHelper::converter($this->errors_msg[2][2],array(
					        		'%%s%%'=>(7-LoginAttempt::$attempts)
					        	));
			          			return $this->setError(2);
			          		}
				            return $this->setError(3);
			          	}
			        }else{
			        	User::lockUser($user_data->id);
			        	LoginAttempt::LimparTentativas($user_data->id);
			        	$this->msg_user_locked=DataHelper::converter($this->msg_user_locked,array(
			        		'%%full_name%%'=>$user_data->full_name
			        	));
						$this->email->enviar($user_data->email,REMETENTE." - Acesso bloqueado por excesso de tentativas",$this->msg_user_locked,array("remetente"=>REMETENTE,"email"=>EMAIL_REMETENTE));
						return $this->setError(4);
					}
				}
			}else return $this->setError(5);
		}

		/**
		 * Método de logout de usuários
		 */
		public static function logout(){
			$_SESSION = array();
			$params = session_get_cookie_params();
			setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
			session_destroy();
			GeneralHelper::go(SITE);
		}

		/**
		 * Valida a autenticação e redireciona mediante o estado do usuário
		 * @param  boolean $redirect Parâmetro que define se é uma página pública ou não
		 */
		public static function redirectCheck($redirect=false){
			if($redirect && self::login_check())
				GeneralHelper::go(SITE.'home');
			elseif(!self::login_check())
				if(!$redirect)
					self::logout();
		}

		/**
		 * Valida a permissão de acesso para o nível do usuário atual
		 * @param  mist  $roles	 String ou Array com role(s) permitida(s)
		 */
		public static function role_check($roles){
			self::redirectCheck();
			$i=0;
			if(is_array($roles) && !empty($roles)){
				foreach ($roles as $role){
					if(self::userActive()->role == $role)
						$i++;
				}
			}elseif(!empty($roles)){
				if(self::userActive()->role == $roles)
						$i++;
			}
			if($i==0){
				GeneralHelper::go(SITE.'home');
				exit();
			}
		}

		/**
		 * Método responsável por verificar se o usuário está logado
		 * @return boolean Status da autenticação
		 */
		public static function login_check() {
		   if(isset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['login_string'])) {
		     $logged=self::userActive();
		     if(!is_null($logged))
		     	return((hash('sha512', $_SESSION['username'].$_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']) == $_SESSION['login_string']) ? true : false);
		   }
		}	

		/**
		 * Método de atribuição de erros
		 * @param int $code Código do erro
		 */
		public function setError($code){
			if(!array_key_exists($code, $this->errors_msg))
				return false;
			$this->errors=$this->errors_msg[$code];
			return false;
		}

		/**
		 * Método responsável por retornar os erros
		 * @return array Array com erros modelado para o AlertHelper
		 */
		public function getErrors(){
			return $this->errors;
		}

		/**
		 * Método de validação final do processo
		 * @return boolean Status do processo de autenticação
		 */
		public function check(){
			if(!empty($this->errors))
				return false;
			return true;
		}

		/**
		 * Método responsável por retornar o objeto do usuário autenticado
		 * @return object Objeto do usuário logado
		 */
		public static function userActive(){
			return (isset($_SESSION["user_id"]) ? User::find_by_id($_SESSION["user_id"]) : null);
		}
	}