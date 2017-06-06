<?php 
	class Controller{

		/**
		 * Injeção da Aplicação
		 * @var object
		 */
		protected $app;

		/**
		 * Injeção do Response
		 * @var object
		 */
		protected $response;

		/**
		 * Injeção da View
		 * @var object
		 */
		protected $view;

		/**
		 * Injeção do Email Helper
		 * @var object
		 */
		public $email;

		/**
		 * Configurações genéricas para todas as actions
		 * @var array
		 */
		public $data=array();


		/**
		 * Método Construtor
		 */
		public function __construct(){
			//Instância dos objetos injetados
			$this->app=new App;
			$this->response=new Response;
			$this->view=new View;
			$this->email=new EmailHelper;
		}
		
		/**
		 * Método responsável pela renderização da VIEW
		 * @param  string  $view     Nome do arquivo, sem extensão, a ser utilizado como VIEW
		 * @param  array   $data     Parâmetros que serão passados como variáveis para a VIEW
		 * @param  boolean $template Define se a VIEW será um miolo englobado por Header e Footer
		 * @param  string  $custom_header   Define um Header customizado
		 * @param  string  $custom_footer   Define um Footer customizado
		 * @param  array   $custom_css      Links de arquivos CSS a serem importados na VIEW
		 * @param  array   $custom_js       Links de arquivos JS a serem importados na VIEW
		 */
		protected function view($view,$data = array(),$template = true, $custom_header='',$custom_footer='',array $custom_css=array(),array $custom_js=array()){
			
			//Configuração dos parâmetros que serão passados para a VIEW
			$data=!is_array($data) ? array() : $data;
			$data=array_merge($this->view->config,$this->data,$data);
			$data['title']=isset($data['title']) ? TITLE.' - '.$data['title'] : TITLE;
			$data['message']=isset($data['message']) ? $this->view->alert->show($data['message']) : '';

			//Extract que transforma os parâmetros em variáveis disponíveis para a VIEW
			if(count($data) > 0)
				extract($data, EXTR_PREFIX_ALL, 'view');

			//Inclusão de ASSETS
			$add_css=$this->view->assets('css',$custom_css);
			$add_js=$this->view->assets('js',$custom_js);

			//Mecanismo de template
			if(!$template){
				require_once(VIEWS.$view.VIEW_EXTENSION);
				exit();
			}
			require_once(VIEWS.'Header'.$custom_header.VIEW_EXTENSION);
			require_once(VIEWS.$view.VIEW_EXTENSION);
			require_once(VIEWS.'Footer'.$custom_footer.VIEW_EXTENSION);
			exit();
		}
		
		/**
		 * Método responsável pelo redirecionamento
		 * @param  string $url Link de redirecionamento
		 */
		public function redirectTo($url){
			$this->response->redirectTo($url);
		}
	}