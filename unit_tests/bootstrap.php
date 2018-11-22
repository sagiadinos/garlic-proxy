<?php
/*************************************************************************************
 * basil-proxy: A proxy solution for Digital Signage SMIL Player
 * Copyright (C) 2018 Nikolaos Sagiadinos <ns@smil-control.com>
 * This file is part of the basil-proxy source code
 *
 * This program is free software: you can redistribute it and/or  modify
 * it under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *************************************************************************************/

$system_dir = realpath("../") . DIRECTORY_SEPARATOR;

require_once('PHPUnitUtils.php');
require_once('../classes/framework/Autoloader.php');
$loader = new \Thymian\framework\Autoloader();
$loader->register()
	->addNamespace('\Thymian','../classes/')
	->addNamespace('\Basil','../classes/');


// we will need the _SESSION variable in some tests
session_start();

// use this to include the db_bootstrap file
define('_TestLibPath', $system_dir . 'unit_tests/');
define("_ResourcesPath", _TestLibPath.'resources');
