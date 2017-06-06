<?php
	
	/**
	 * Para definir a BASE como a raiz, ou seja,
	 * o HXPHP não estando em uma subpasta, a BASE deve ser igual a '/'
	 */

	/**
	 * Constate para ambiente local
	 */
	if($_SERVER['SERVER_NAME'] === 'localhost'){
		/**
		 * Forma correta: /pasta/
		 * Formas incorretas: 'pasta/' ou 'pasta' ou '/pasta'
		 */
		define('BASE','/jheron/');
	}
	/**
	 * Constante para ambiente remoto
	 */
	else{
		define('BASE','/');
	}

	//Constantes que definem as URLs fundamentais da aplicação
	$site_config=array(
		'SITE' => 'http://'.$_SERVER['SERVER_NAME'].BASE
	);

	//Constantes de configurações para a aplicação
	$app_config=array(
		'VERSION' => '1.0',
		'DS' => '/',
		'VIEW_EXTENSION' => '.phtml',
		'CONTROLLER_NOT_FOUND' => 'Error404Controller'
	);

	//Constantes que definem os diretórios principais do HXPHP
	$main_directories=array(
		'MODELS' => 'app/models/',
		'CONTROLLERS' => 'app/controllers/',
		'VIEWS' => 'app/views/',
		'MODULES' => 'system/Modules/',
		'HELPERS' => 'system/Helpers/',
		'PUBLICDIR' => $site_config['SITE'].'public/'
	);

	//Constantes que definem os diretórios secundários do HXPHP
	$secondary_directories=array(
		'ASSETS' => $main_directories['PUBLICDIR'].'assets/',
		'IMG' => $main_directories['PUBLICDIR'].'img/',
		'CSS' => $main_directories['PUBLICDIR'].'css/',
		'JS' => $main_directories['PUBLICDIR'].'js/'
	);

	//Constante que define o título prefixado da aplicação
	$title_config=array(
		'TITLE' => 'J.H. Dilivery'
	);

	//Constantes que configuram os headers do sistema de envio de e-mails
	$email_config=array(
		'REMETENTE' => 'HXPHP Framework',
		'EMAIL_REMETENTE' => 'no-reply@'.str_replace("www.", "", $_SERVER['SERVER_NAME'])
	);

	//Declaração das constantes
	$constants=array_merge($site_config,$app_config,$main_directories,$secondary_directories,$title_config,$email_config);
	foreach($constants as $key => $value)
		define($key,$value);