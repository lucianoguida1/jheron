<?php
	class Request{
		
		/**
		 * Atributos
		 * @var null
		 */
		private $server;
		public  $controller;
		public  $action;
		public  $params=array();

		/**
		 * Método construtor
		 */
		public function __construct(){
			$this->initialize();
			return $this;
		}

		/**
		 * Método responsável por definir os parâmetros do mecanismo MVC
		 * @return object Retorna o objeto com as propriedades definidas
		 */
		public function initialize(){
			
			$this->server = $_SERVER;

			$explode = array_values(array_filter(explode('/', $_SERVER['REQUEST_URI'])));
			if(isset(explode('/', $_SERVER['REQUEST_URI'])[4])){
				if(explode('/', $_SERVER['REQUEST_URI'])[4]==0){$explode[] = 0;}
			}
			if(isset($explode[0]) && $explode[0] == str_replace('/','',BASE)){
				unset($explode[0]);
				$explode=array_values($explode);
			}

			if (count($explode) == 0) {
				$this->controller = 'IndexController';
				$this->action = 'indexAction';

				return $this;
			}

			if (count($explode) == 1) {
				$this->controller = ucwords(str_replace("-","",$explode[0])).'Controller';
				$this->action = 'indexAction';

				return $this;
			}

			$this->controller = ucwords(str_replace("-","",$explode[0])).'Controller';
			$this->action = str_replace("-", "_", $explode[1]).'Action';
			
			unset($explode[0], $explode[1]);
			$this->params = array_values($explode);
		}
	}