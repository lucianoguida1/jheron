<?php
	class GeneralHelper{
		
		/**
		 * Método responsável pela exibição de dados DEBUGADOS
		 * @param  mist $data Variável que será "debugada"
		 */
		public static function show($data){
			echo "<pre>";
			print_r($data);
			echo "</pre>";
		}

		/**
		 * Método responsável pelo redirecionamento da aplicação
		 * @param  string $url URL para aonde a aplicação deve ser redirecionada		
		 */
		public static function go($url){
			header("location: $url");
		}
	}