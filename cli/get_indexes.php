<?php
/*************************************************************************************
 * basil-proxy: A proxy solution for Digital Signage SMIL Player
 * Copyright (C) 2018 Nikolaos Saiadinos <ns@smil-control.com>
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

$PlayerModel     = new \Basil\model\PlayerModel($Configuration->getFullPathValuesByKey('player_path'));
$player_list     = $PlayerModel->scanRegisteredPlayer();

$IndexModel      = new \Basil\model\IndexModel($Configuration->getFullPathValuesByKey('index_path'));
$Curl            = new \Thymian\framework\Curl();
$IndexController = new \Basil\SmilIndexController($PlayerModel, $IndexModel, $Configuration,  $Curl);

$MediaModel      = new \Basil\model\MediaModel($Configuration->getFullPathValuesByKey('index_path'));

foreach ($player_list as $file_info)
{
	if (!$file_info->isDot())
	{
		$uuid = $file_info->getBasename('.reg');
		$IndexController->requestIndexForRegisteredPlayer($uuid);

		if (!$IndexController->isNewIndex())
			continue;

		$index = $IndexController->readDownloadedIndex();



		// parse index
		// download media
		// change paths for media in index

		$IndexModel->saveIndex($uuid, $index);
	}
}