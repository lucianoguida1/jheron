<?php
	class Tools{
		
		/**
		 * Método responsável pelo início de sessão
		 */
		public static function sec_session_start() {
	        ini_set('session.use_only_cookies', 1);
	        $cookieParams = session_get_cookie_params();
	        session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], false, true); 
	        session_name('sec_session_id');
	        session_start();
		}

		/**
		 * Método responsável por criptografar a senha do usuário no padrão HXPHP
		 * @param  string $password Senha do usuário
		 * @param  string $salt     Código alfanumérico
		 * @return array            Array com o SALT e a SENHA
		 */
		public static function hashHX($password,$salt=null){
			
			if(is_null($salt))
				$salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));

			$password = hash('sha512', $password.$salt);
			
			return array(
				'salt'=>$salt,
				'password'=>$password
			);
		}
	}