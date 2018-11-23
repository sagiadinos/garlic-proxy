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

namespace Basil;

class ViewController extends BaseController
{
	public function view($view = 'list')
	{
		$list        = $this->getModel()->scanRegisteredPlayer();
		$system_dir = $this->getConfiguration()->getSystemDir();
		require_once ($system_dir.'/templates/header.php');
		require_once ($system_dir.'/view/'.$view.'.php');
		require_once ($system_dir.'/templates/footer.php');
	}
}