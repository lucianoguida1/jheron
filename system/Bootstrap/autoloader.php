<?php

	/**
	 * Autoload
	 * @param  string $file [Este valor é obtido automaticamente quando uma classe é instanciada]
	 */
	function loader($file) {

		/**
		 * Extensão dos arquivos que serão importados
		 * @var string
		 */
		$extension='.php';

		/**
		 * Tratamento para o nome do arquivo
		 * @var string
		 */
		$file=str_replace("\\", "/", $file);
		
		/**
		 * Diretórios que serão verificados
		 * @var array
		 */
	    $directories=array(
	    	CONTROLLERS,
	    	'system/',
	    	'system/Http/',
	    	MODULES,
	    	HELPERS
	    );

	    foreach($directories as $path){
	    	// ClassName.php
	    	if(file_exists($file.$extension)){
	    		require_once($file.$extension);
	    	}
	    	// Pasta/ClassName.php
	    	if(file_exists($path.$file.$extension)){
	    		require_once($path.$file.$extension);
	    	}
	    	// Pasta/ClassName/ClassName.php
	    	elseif(file_exists($path.$file.DS.$file.$extension)){
	    		require_once($path.$file.DS.$file.$extension);
	    	}
	    }
	}
	spl_autoload_register('loader');