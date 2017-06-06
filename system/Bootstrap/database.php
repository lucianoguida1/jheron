<?php
	if($_SERVER['SERVER_NAME'] === 'localhost'){
		$db_config=array(
			'HOST'=>'localhost',
			'USER'=>'root',
			'PASS'=>'',
			'DBNAME'=>'app'
		);
	}else{
		$db_config=array(
			'HOST'=>'mysql.hostinger.com.br',
			'USER'=>'u536283677_jhero',
			'PASS'=>'K3X1osncOV8U',
			'DBNAME'=>'u536283677_jhero'
		);
	}
	//Declaração das constantes
	foreach($db_config as $key => $value)
		define($key,$value);