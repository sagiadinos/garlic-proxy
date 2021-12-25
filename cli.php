<?php
/*************************************************************************************
 * garlic-proxy: A proxy solution for Digital Signage SMIL Player
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

try
{
	$system_dir = realpath("./") . DIRECTORY_SEPARATOR;

	require_once ('./bootstrap.php');

	// we can put here a cli parameter lib if necessary
	// currently we will get all the indexes from cmd
	require_once('view/cli/get_indexes.php');

}
catch(\Exception $e)
{
	echo $e->getTraceAsString();
	echo $e->getMessage() . "\n";
	exit(255);
}