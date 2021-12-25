<?php
/*************************************************************************************
garlic-proxy: A proxy solution for Digital Signage SMIL Player
Copyright (C) 2021 Nikolaos Sagiadinos <ns@smil-control.com>
This file is part of the garlic-proxy source code
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
 * @var $Configuration 				\Garlic\helper\Configuration
 */

use Garlic\controller\AsyncController;
use Garlic\model\PlayerModel;

$system_dir = realpath("../");

try
{
	require_once('../bootstrap.php');

	$PlayerModel = new PlayerModel($Configuration->getFullPathValuesByKey('player_path'));
	$Controller = new AsyncController($PlayerModel, $Configuration);
	// validation ToDo: write a small lib
	$site = '';
	if (isset($_GET['site']) && $_GET['site'] == 'get_index')
		$site = $_GET['site'];
	else
		throw new Exception(strip_tags($_GET['site']).' is unknown');

	$Controller->site($site);

}
catch(Exception $e)
{
	echo $e->getMessage();
	exit();
}