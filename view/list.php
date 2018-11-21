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

/**
 * @var $list 				\DirectoryIterator
 */

$table  = '';
$UserAgent   = new \Basil\helper\UserAgent();
$PlayerModel = $this->getModel(); // Do not forget, that we are insite of the ViewController!
foreach ($list as $file_info)
{
	if (!$file_info->isDot())
	{
		$UserAgent->setInfoFromAgentString($PlayerModel->getContentOfFile($file_info));
		$player_name = $UserAgent->getName();

		$table .= '
		<tr>
			<td>'.$player_name.'</td>
			<td>'.$file_info->getFilename().'</td>
			<td>'.date('Y-m-d H:i:s', $file_info->getCTime()).'</td>
			<td>'.date('Y-m-d H:i:s', $file_info->getATime()).'</td>
		</tr>';
	}
}
if ($table != '')
	$table =  '
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
				created
			</th>
			<th>
				last access
			</th>
		</tr>
	</thead>
	<tbody>'
		.$table.
	'</tbody>
</table>';

echo $table;