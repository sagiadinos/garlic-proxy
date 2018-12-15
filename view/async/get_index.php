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

$PlayerModel   = $this->getModel(); // Do not forget, that we are inside of the AsyncController
$Configuration = $this->getConfiguration();

$player_list     = $PlayerModel->scanRegisteredPlayer();

$IndexModel      = new \Basil\model\IndexModel($Configuration->getFullPathValuesByKey('index_path'));
$IndexController = new \Basil\controller\SmilIndexController($PlayerModel, $IndexModel, $Configuration,  new \Thymian\framework\Curl());

$MediaModel      = new \Basil\model\MediaModel($Configuration->getFullPathValuesByKey('index_path'));

if (isset($_GET['uuid']))
	$uuid = strip_tags($_GET['uuid']); //
else
	throw new \Exception('No uuid was set');

$IndexController->requestIndexForRegisteredPlayer($uuid);
if (!$IndexController->isNewIndex())
	exit;

$Curl              = new \Thymian\framework\Curl();
$Curl->setUserAgent($PlayerModel->load($uuid));
$RemoteFiles       = new \Basil\model\RemoteFiles($Curl, $Configuration);
$SmilMediaReplacer = new \Basil\helper\SmilMediaReplacer($IndexController->readDownloadedIndex(), $Curl, $Configuration);
$SmilMediaReplacer->findMatches();
$SmilMediaReplacer->replace($RemoteFiles);
$IndexModel->saveIndex($uuid, $SmilMediaReplacer->getSmil());

