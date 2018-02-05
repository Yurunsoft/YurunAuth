<?php
return array(
	'DB' => array (
		'default'	=>	array(
			'type' 		=> 'PDOMysql',
			'option'	=>	array(
				'host' => '127.0.0.1',
				'port' => '3306',
				'username' => 'root',
				'password' => 'root',
				'dbname' => 'db_auth',
				'prefix' => 'tb_',
				'charset' => 'utf8',
			)
		),
	),
	'DEFAULT_DB'	=>	'default',
);