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
 * @var $list 				\DirectoryIterator
 */

$table  = '';
$UserAgent   = new \Basil\helper\UserAgent();
$PlayerModel = $this->getModel(); // Do not forget, that we are inside of the ViewController!
$IndexModel  = new \Basil\model\IndexModel($this->getConfiguration()->getFullPathValuesByKey('index_path'));
foreach ($list as $file_info)
{
	if (!$file_info->isDot())
	{
		$UserAgent->setInfoFromAgentString($PlayerModel->load($file_info->getBasename('.reg')));
		$player_name = $UserAgent->getName();
		$uuid        = $file_info->getBasename('.reg');

		$table .= '
			
		<tr id="uuid_'.$uuid.'">
			<td><button onclick="refreshIndex(\''.$uuid.'\')" title="get index now">&#128472;</button> '.$player_name.'</td>
			<td class="filename">'.$file_info->getFilename().'</td>
			<td class="create_datetime">'.date('Y-m-d H:i:s', $file_info->getCTime()).'</td>
			<td class="last_update">'.$IndexModel->lastUpdate($uuid).'</td>
		</tr>';
	}
}
if ($table != '')
	$table =  '
<!-- not implemented <button>refresh all</button> -->
<table>
	<thead>
		<tr>
			<th>
				Player name
			</th>
			<th>
				Filename
			</th>
			<th>
				registered
			</th>
			<th>
				last index update
			</th>
		</tr>
	</thead>
	<tbody>'
		.$table.
	'</tbody>
</table>
<script src="resources/js/index.js" type="text/javascript"></script>
';

echo $table;
