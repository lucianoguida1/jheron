<?php

	/**
	 * @name: Lost_Password
	 * @description: Módulo responsável por gerenciar todo o mecanismo de recuperação de senha
	 * @author: Bruno C. Santos
	 * @version: 1.0
	 */

	class Lost_Password{

		/**
		 * Injeção do Helper de E-mail
		 * @var object
		 */
		protected $email;

		/**
		 * Atributos
		 * @var null
		 */
		public $link=null;
		public $token=null;
		public $user=null;
		public $errors=array();

		/**
		 * Mensagens de erro padronizadas para o AlertHelper
		 * @var array
		 */
		public $errors_msg=array(
			array(
				'danger',
				'Oops! Nenhum usuário encontrado',
				'Por favor, verifique o nome de usuário informado e tente novamente!'
			),
			array(
				'danger',
				'Oops! E-mail não enviado',
				'Por favor, contate o administrador do sistema para corrigir este problema.'
			),
			array(
				'danger',
				'Oops! Código inválido ou expirado!',
				'Por favor, repita o processo de redefinição de senha.'
			),
			array(
				'danger',
				'Oops! Usuário inválido ou expirado!',
				'Por favor, repita o processo de redefinição de senha.'
			)
		);

		/**
		 * Mensagem de sucesso padronizada para o AlertHelper
		 * @var array
		 */
		public $success_run_msg=array(
			'success',
			'Uhull! O link de redefinição de sua senha foi enviado com sucesso!',
			'Verifique a sua caixa de entrada  ou de SPAM para encontrar nosso e-mail.'
		);

		/**
		 * Mensagem de sucesso padronizada para o AlertHelper
		 * @var array
		 */
		public $success_save_msg=array(
			'success',
			'Uhull! Sua senha foi redefinida com sucesso!',
			'Por favor, faça login com seus novos dados.'
		);

		/**
		 * Mensagem que será enviada quando um usuário solicitar redefinição de senha
		 * TAGS PERMITIDAS:
		 * - %%full_name%% = Atribui o nome completo do usuário
		 * - %%link_reset%% = Atribui o link de redefinição de senha
		 * @var string
		 */
		public $msg_template='Olá %%full_name%%, <br /> Recentemente você solicitou um link para redefinir sua senha, para concluir o processo, acesse: <br /> <a href="%%link_reset%%" target="_blank">%%link_reset%%</a> <br /> Atenciosamente,<br/>';


		/**
		 * Método construtor
		 * @param string $username Nome de usuário
		 * @param string $link     Link que será concatenado com o token para redefinir a senha
		 * @param string $token    Token é informado quando o processo é de validação e confirmação
		 */
		public function __construct($username,$link,$token=null){
			//Instância dos objetos injetados
			$this->email=new EmailHelper;

			if(is_null($token)){
				//Processo de obtenção do Token
				$this->user=User::find_by_username($username);
				if(!is_null($this->user)){
					$this->link=$link;
					$this->generateToken($username);
					$this->run();
				}else $this->setError(0);
			}else{
				//Processo de validação do Token
				$this->validateToken($token);
			}
		}

		/**
		 * Método responsável por gerar o token
		 */
		private function generateToken(){
			$this->token = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
		}

		/**
		 * Método responsável por verificar se já existe uma solicitação pendente para este usuário
		 * @param  integer $user_id ID do usuário verificado
		 * @return boolean          Retorna o status de existência de solicitações do usuário determinado
		 */
		private function checkExists($user_id){
			return ((!is_null(LostPassword::find_by_user_id($user_id))) ? true : false);
		}

		/**
		 * Método responsável pelo envio do e-mail e registro da solicitação de redefinição no banco de dados
		 */
		private function run(){
			$this->msg_template=DataHelper::converter($this->msg_template,array(
        		'%%full_name%%'=>$this->user->full_name,
        		'%%link_reset%%'=>$this->link.$this->token
        	));
			$email_enviar=$this->email->enviar(
				$this->user->email,
				REMETENTE. ' - Redefinição de senha',
				$this->msg_template.REMETENTE,
				array(
					'remetente'=>REMETENTE,
					'email'=>EMAIL_REMETENTE
				)
			);
			if($email_enviar[0]){
				$user_id=$this->user->id;
				if($this->checkExists($user_id))
					LostPassword::delete_all(array("conditions"=>array("user_id=?",$user_id)));
					LostPassword::create(array(
						'user_id'=>$this->user->id,
						'IP'=>$_SERVER['REMOTE_ADDR'],
						'token'=>$this->token,
						'status'=>0
					));
			}else $this->setError(1);
		}

		/**
		 * Método de validação de token
		 * @param  string $token Código alfanumérico
		 */
		private function validateToken($token){
			$lostRegister=LostPassword::find_by_token_and_status($token,0);
			if(is_null($lostRegister)){
				$this->setError(2);
			}else{
				$this->user=User::find($lostRegister->user_id);
				if(is_null($this->user))
					$this->setError(3);
			}
		}

		/**
		 * Método responsável por atualizar a senha do usuário mediante validação completa
		 * @param  string $password Nova senha do usuário
		 * @return boolean          Status da operação
		 */
		public function save($password){
			if($this->check() && !is_null($this->user)){
				$criptografar=Tools::hashHX($password);
				$this->user->update_attributes(array(
					'salt'=>$criptografar['salt'],
					'password'=>$criptografar['password']
				));
				LostPassword::delete_all(array("conditions"=>array("user_id=?",$this->user->id)));
				return true;
			}
			return false;
		}
		
		/**
		 * Método de atribuição de erros
		 * @param integer $code Código do erro
		 */
		private function setError($code){
			if(!array_key_exists($code, $this->errors_msg))
				return false;
			$this->errors=$this->errors_msg[$code];
		}

		/**
		 * Método de validação final do processo
		 * @return boolean Status do processo
		 */
		private function check(){
			if(!empty($this->errors))
				return false;
			return true;
		}

		/**
		 * Método de exibição final do processo na fase de envio
		 * @return array Mensagem de status modelada para o AlertHelper
		 */
		public function results(){
			if($this->check())
				return $this->success_run_msg;
			return $this->errors;
		}

	}