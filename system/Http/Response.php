<?php
	class Response{

		/**
		 * Método responsável pelo redirecionamento da aplicação
		 * @param  string $url URL para aonde a aplicação deve ser redirecionada		
		 */
		public function redirectTo($url){
			header("location: $url");
		}
	}