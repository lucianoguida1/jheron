<?php
	class App{

		/**
		 * Injeção do Request
		 * @var object
		 */
		public $request;

		/**
		 * Injeção do Response
		 * @var object
		 */
		public $response;
		
		/**
		 * Método Construtor
		 */
		public function __construct(){
			$this->ActiveRecord();
			$this->request=new Request;
			return $this;
		}

		/**
		 * Configuração do ORM
		 */
		protected function ActiveRecord(){
			$cfg = ActiveRecord\Config::instance();
			$cfg->set_model_directory(MODELS);
			$cfg->set_connections(array('development' =>'mysql://'.USER.':'.PASS.'@'.HOST.'/'.DBNAME.'?charset=utf8'));
		}
		
		/**
		 * Método responsável pela execução da aplicação
		 */
		public function run(){

			if(!class_exists($this->request->controller)){
				$this->request->controller=CONTROLLER_NOT_FOUND;
			}

			$app = new $this->request->controller();
			
			if(!method_exists($app, $this->request->action))
				$this->request->action='indexAction';
			
			$action = $this->request->action;

			//Atribuição de parâmetros
			call_user_func_array(array(&$app,$action), $this->request->params);
		}
	}