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

namespace Garlic\controller;

use Garlic\helper\Configuration;
use Garlic\model\PlayerModel;

class BaseController
{
	/**
	 * @var PlayerModel
	 */
	protected $Model;
	/**
	 * @var Configuration
	 */
	protected $Configuration;

	public function __construct(PlayerModel $model, Configuration $config)
	{
		$this->setModel($model)
			 ->setConfiguration($config);
	}

	/**
	 * @return PlayerModel
	 */
	public function getModel()
	{
		return $this->Model;
	}

	/**
	 * @param $Model
	 *
	 * @return $this
	 */
	public function setModel(PlayerModel $Model)
	{
		$this->Model = $Model;
		return $this;
	}

	/**
	 * @return Configuration
	 */
	public function getConfiguration()
	{
		return $this->Configuration;
	}

	/**
	 * @param $Configuration
	 *
	 * @return $this
	 */
	public function setConfiguration(Configuration $Configuration)
	{
		$this->Configuration = $Configuration;
		return $this;
	}

}