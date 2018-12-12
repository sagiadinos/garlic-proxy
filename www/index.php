<?php
/*************************************************************************************
basil-proxy: A proxy solution for Digital Signage SMIL Player
Copyright (C) 2018 Nikolaos Sagiadinos <ns@smil-control.com>
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

/**
 * @var $Configuration 				\Basil\helper\Configuration
 */
$system_dir = realpath("../");

try
{
	require_once('../bootstrap.php');

	$PlayerModel = new \Basil\model\PlayerModel($Configuration->getFullPathValuesByKey('player_path'));
	$UserAgent   = new \Basil\helper\UserAgent();

	// $agent =  'ADAPI/1.0 (UUID:a8294bat-c28f-50af-f94o-800869af5854; NAME:Player with spaces in name) SK8855-ADAPI/2.0.5 (MODEL:XMP-330)';
	$UserAgent->setInfoFromAgentString($_SERVER['HTTP_USER_AGENT']);

	// check if it is a DS-device or a Web browser
	if ($UserAgent->isDsPlayer())
	{
		$Controller = new \Basil\controller\PlayerController($PlayerModel, $Configuration, $UserAgent);
		$Controller->dispatch();
	}
	else // user Browser
	{
		$Controller = new \Basil\controller\ViewController($PlayerModel, $Configuration);
		// validation ToDo: write a small lib
		if (isset($_GET['site']) && ($_GET['site'] == 'list' || $_GET['site'] == 'get_index'))
			$site = $_GET['site'];
		else
			$site = 'list';
		$Controller->view($site);
	}
}
catch(\Exception $e)
{
	echo $e->getMessage();
	exit();
}