<?php
$system_dir = realpath("../") . DIRECTORY_SEPARATOR;

require_once('../classes/framework/Autoloader.php');
$loader = new \Thymian\framework\Autoloader();
$loader->register()
	->addNamespace('\Thymian','../classes/')
	->addNamespace('\Basil','../classes/');

// we will need the _SESSION variable in some tests
session_start();

// use this to include the db_bootstrap file
define('_TestLibPath', $system_dir . 'unit_tests/');
