<?php
	ini_set('display_errors', 1); 
	date_default_timezone_set('America/Sao_Paulo');
	ob_start();

	//Inclusão de arquivos de configuração
	require_once("system/Bootstrap/database.php");
	require_once("system/Bootstrap/constants.php");
	require_once("system/Bootstrap/autoloader.php");

	//Start da sessão
	Tools::sec_session_start();
	
	require_once('system/Modules/ActiveRecord/ActiveRecord.php');

	$app = new App();
	$app->run();