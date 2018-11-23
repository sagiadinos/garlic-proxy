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

namespace Basil\helper;


class Configuration
{
	/**
	 * @var array
	 */
	protected $values = array();
	/**
	 * @var string
	 */
	protected $system_dir = '';


	public function __construct($config_file, $system_dir)
	{
		$this->values     = parse_ini_file($config_file, true);
		$this->system_dir = $system_dir;
	}

	/**
	 * @return string
	 */
	public function getSystemDir()
	{
		return $this->system_dir;
	}

	/**
	 * @return string
	 */
	public function getIndexServer()
	{
		return $this->values['index_server'];
	}

	/**
	 * @return string
	 */
	public function getHomeDomain()
	{
		return $this->values['home_domain'];
	}


	/**
	 * @param $key
	 *
	 * @return string
	 */
	public function getFullPathValuesByKey($key)
	{
		return $this->system_dir.'/www/'.$this->getValuesByKey($key);
	}

	/**
	 * @param $key
	 *
	 * @return string
	 */
	public function getValuesByKey($key)
	{
		if (!array_key_exists($key, $this->values['Paths']))
		{
			throwException($key. ' not exists');
		}

		return $this->values['Paths'][$key];
	}
}