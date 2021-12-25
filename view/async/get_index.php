<?php
/*************************************************************************************
 * Garlic-proxy: A proxy solution for Digital Signage SMIL Player
 * Copyright (C) 2021 Nikolaos Sagiadinos <ns@smil-control.com>
 * This file is part of the garlic-proxy source code
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

$IndexModel      = new \Garlic\model\IndexModel($Configuration->getFullPathValuesByKey('index_path'));
$IndexController = new \Garlic\controller\SmilIndexController($PlayerModel, $Configuration,  new \Thymian\framework\Curl());

if (isset($_GET['uuid']))
	$uuid = strip_tags($_GET['uuid']); //
else
	throw new \Exception('No uuid was set');

$IndexController->requestIndexForRegisteredPlayer($uuid);
if (!$IndexController->isNewIndex())
	exit;

$Curl              = new \Thymian\framework\Curl();
$Curl->setUserAgent($PlayerModel->load($uuid));
$RemoteFiles       = new \Garlic\model\RemoteFiles($Curl, $Configuration);
$SmilMediaReplacer = new \Garlic\helper\SmilMediaReplacer($IndexController->readDownloadedIndex());
$SmilMediaReplacer->findMatches();
$SmilMediaReplacer->replace($RemoteFiles);
$IndexModel->saveIndex($uuid, $SmilMediaReplacer->getSmil());

