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


use PHPUnit\Exception;

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
		if (!file_exists($config_file))
		{
			throw new \RuntimeException('Config file \''.$config_file.'\' not exists');
		}

		$this->values     = parse_ini_file($config_file, true);
		if (empty($system_dir))
		{
			throw new \RuntimeException('System_dir cannot be empty');
		}

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
		$uri = $this->getIndexServerUri();
		$ar = parse_url($uri);
		if (array_key_exists('scheme', $ar))
		{
			$scheme = $ar['scheme'];
		}
		else
		{
			$scheme = 'http';
		}
		return $scheme.'://'.$ar['host'];
	}

	/**
	 * @return string
	 */
	public function getIndexServerUri()
	{
		if (!array_key_exists('index_server_uri', $this->values))
		{
			throw new \RuntimeException('Key index_server_uri not exists in config');
		}
		return $this->values['index_server_uri'];
	}


	/**
	 * @return string
	 */
	public function getHomeDomain()
	{
		if (!array_key_exists('home_domain', $this->values))
		{
			throw new \RuntimeException('Key home_domain not exists in config');
		}
		return $this->values['home_domain'];
	}


	/**
	 * @param $key
	 *
	 * @return string
	 */
	public function getFullPathValuesByKey($key)
	{
		return $this->getSystemDir().'/www/'.$this->getPathValuesByKey($key);
	}

	/**
	 * @param $key
	 *
	 * @return string
	 */
	public function getPathValuesByKey($key)
	{
		if (!array_key_exists($key, $this->values['Paths']))
		{
			throw new \RuntimeException($key. ' not exists');
		}

		return $this->values['Paths'][$key];
	}
}