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

namespace Garlic\helper;


class UserAgent
{
	/**
	 * @var string
	 */
	protected $agent_string = '';
	/**
	 * @var array
	 */
	protected $info = array();
	/**
	 * @var int
	 */
	protected $model_id = 0;

	/**
	 * @var bool
	 */
	protected $is_ds_player = true;

	/**
	 * @return string
	 */
	public function getAgentString()
	{
		return $this->agent_string;
	}

	/**
	 * @param string $agent_string
	 *
	 * @return UserAgent
	 */
	public function setAgentString($agent_string)
	{
		$this->agent_string = $agent_string;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getUuid()
	{
		return $this->info['uuid'];
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->info['name'];
	}

	/**
	 * @return array
	 */
	public function getInfo()
	{
		return $this->info;
	}

	/**
	 * @return bool
	 */
	public function isDsPlayer()
	{
		return $this->is_ds_player;
	}

	public function setInfoFromAgentString($agent_string)
	{
		$this->setAgentString($agent_string);
		// ADAPI/1.0 (UUID:a8294bat-c28f-50af-f94o-800869af5854; NAME:Player with spaces in name) SK8855-ADAPI/2.0.5 (MODEL:XMP-330)
		if (preg_match('/([^ ]+) \(UUID:(.*?); NAME:(.*?)\) (.*?) \(MODEL:(.*?)\)/', $agent_string, $matches))
		{
			$this->setInfo(array(
							   'uuid' => $matches[2],
							   'firmware_version' => $matches[4],
							   'name' => urldecode($matches[3]),
							   'model' => $matches[5]
						   ));
			$this->is_ds_player = true;
		}
		// SmartAPI/1.0 (UUID:cc009f47-5a8d-42b4-af5a-1865710c05ba; NAME:05B200T100223; VERSION:v1.0.16; MODEL:TD-1050)
		elseif (preg_match('/([^ ]+) \(UUID:(.*?); NAME:(.*?); VERSION:(.*?); MODEL:(.*?)\)/', $agent_string, $matches))
		{
			$this->setInfo(array(
							   'uuid' => $matches[2],
							   'firmware_version' => $matches[4],
							   'name' => urldecode($matches[3]),
							   'model' => $matches[5]
						   ));
			$this->is_ds_player = true;
		}
		elseif (preg_match('/([^ ]+) \(UUID:(.*?)\) (.*?)-(.*?) \(MODEL:(.*?)\)/', $agent_string, $matches))
		{
			$this->setInfo(array(
							   'uuid' => $matches[2],
							   'firmware_version' => $matches[4],
							   'name' => urldecode($matches[3]),
							   'model' => $matches[5]
						   ));
			$this->is_ds_player = true;
		}
		else
		{
			$this->setInfo(array(
							   'uuid' => '',
							   'firmware_version' => '',
							   'name' => '',
							   'model' => ''
						   ));
			$this->is_ds_player = false;
		}
		return $this;
	}

	/**
	 * @param array $info
	 *
	 * @return UserAgent
	 */
	protected function setInfo($info)
	{
		$this->info = $info;
		return $this;
	}

}