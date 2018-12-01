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
define('_debug', true);
if (_debug)
{
	error_reporting(-1);
	ini_set('display_errors', 1);
}
else
{
	ini_set('display_errors', 0);
	error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
}

require_once('classes/framework/Autoloader.php');
$loader = new \Thymian\framework\Autoloader();
$loader->register()
	   ->addNamespace('\Thymian',$system_dir.'/classes/')
	   ->addNamespace('\Basil',$system_dir.'/classes/');

$Configuration  = new \Basil\helper\Configuration($system_dir.'/configuration/main.ini', $system_dir);

$Directory      = new \Basil\model\Directory();
$Directory->createDirectoryIfNotExist($Configuration->getFullPathValuesByKey('player_path'));
$Directory->createDirectoryIfNotExist($Configuration->getFullPathValuesByKey('index_path'));
$Directory->createDirectoryIfNotExist($Configuration->getFullPathValuesByKey('media_path'));
