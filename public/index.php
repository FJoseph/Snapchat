<?php

	define('WEBROOT', dirname(__FILE__));
	define('ROOT', dirname(WEBROOT));
	define('DS', DIRECTORY_SEPARATOR);
	define('CORE', ROOT.DS.'app');
	define('CONFIG', ROOT.DS.'config');
	define('LIBS', CORE.DS.'libs');
	define('BASE_URL', dirname(dirname($_SERVER['SCRIPT_NAME'])));
	define('NAME', 'Snapchat');

	require CORE.DS.'Requires.php';
	new Dispatcher();