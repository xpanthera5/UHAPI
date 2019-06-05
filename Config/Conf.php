<?php
	namespace Config;

	/**
	 * Conf
	 */
	class Conf
	{
		static $debug = 1;
		static $databases = [
			'default' => [
				'host'		=> 'localhost',
				'database'	=> 'uhtec',
				'login'		=> 'root',
				'password'	=> ''
			]
		];
	}
 ?>