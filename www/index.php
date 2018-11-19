<?php
/*************************************************************************************
basil-proxy: A proxy solution for Digital Signage SMIL Player
Copyright (C) 2018 Nikolaos Saghiadinos <ns@smil-control.com>
This file is part of the basil-proxy source code
This program is free software: you can redistribute it and/or  modify
it under the terms of the GNU Affero General Public License, version 3,
as published by the Free Software Foundation.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.
You should have received a copy of the GNU Affero General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
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

require_once('../classes/Autoloader.php');
$loader = new \Basil\Autoloader();
$loader->register()->addNamespace('\Basil','../classes/');


$Configuration  = new \Basil\Configuration();
$PlayerModel    = new \Basil\PlayerModel($Configuration->getFullPathValuesByKey('player_path'));
$UserAgent      = new \Basil\UserAgent();

// $agent =  'ADAPI/1.0 (UUID:a8294bat-c28f-50af-f94o-800869af5854; NAME:Player with spaces in name) SK8855-ADAPI/2.0.5 (MODEL:XMP-330)';

$UserAgent->setInfoFromAgentString($_SERVER['HTTP_USER_AGENT']);

// check if it is a DS-device or a Web browser
if ($UserAgent->isDsPlayer())
{
	$Controller = new \Basil\PlayerController($PlayerModel, $Configuration, $UserAgent);
	$Controller->dispatch();
}
else // user Browser
{
	$Controller = new \Basil\ViewController($PlayerModel, $Configuration);
	$Controller->view();
}
