<?php
	class View{

		/**
		 * Injeção da Aplicação
		 * @var object
		 */
		protected $app;

		/**
		 * Injeção do AlertHelper
		 * @var object
		 */
		public $alert;

		/**
		 * Configuração padrão para controllers
		 * @var array
		 */
		public $config=array();

		/**
		 * Método Construtor
		 */
		public function __construct(){
			//Instância dos objetos injetados
			$this->app=new App;
			$this->alert=new AlertHelper;

			$user=Auth::userActive();
			$this->config=array(
				'user'=>$user,
				'menu'=>MenuHelper::render($user,$this->app->request->controller)
			);
			return $this;
		}

		/**
		 * Método responsável pela inclusão de arquivos customizados
		 * @param  string $type          Tipo de arquivo incluso, como: css ou js
		 * @param  array  $custom_assets Links dos arquivos que serão incluídos
		 * @return string                HTML formatado de acordo com o tipo de arquivo
		 */
		public function assets($type,array $custom_assets=array()){
			$add_assets='';
			switch ($type) {
				case 'css':
					$tag='<link type="text/css" rel="stylesheet" href="%s">'."\n\r";
					break;
				case 'js':
					$tag='<script type="text/javascript" src="%s"></script>'."\n\r";
					break;
			}
			if(count($custom_assets) > 0)
				foreach($custom_assets as $file)
					$add_assets.=sprintf($tag,$file);
			return $add_assets;
		}
	}