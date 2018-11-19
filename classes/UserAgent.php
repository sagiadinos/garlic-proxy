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

namespace Basil;


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
	 * @return array
	 */
	public function getUuid()
	{
		return $this->info['uuid'];
	}

	/**
	 * @return array
	 */
	public function getName()
	{
		return $this->info['name'];
	}

	/**
	 * @return array
	 */
	public function getFirmware()
	{
		return $this->info['firmware'];
	}

	/**
	 * @return bool
	 */
	public function isDsPlayer()
	{
		return $this->is_ds_player;
	}

	/**
	 * @param $model_name
	 * @return $this
	 */
	public function detectModelId($model_name)
	{
		switch ($model_name)
		{
			case 'XMP-120':
			case 'XMP-130':
			case 'XDS-101':
			case 'XDS-104':
			case 'XDS-151':
				$this->setModelId(PlayerModel::PLAYER_MODEL_IADEA_XMP1X0);
				break;
			case 'XMP-320':
			case 'XMP-330':
			case 'XMP-340':
			case 'XDS-195':
			case 'XDS-245':
			case 'GDATA-1100':
				$this->setModelId(PlayerModel::PLAYER_MODEL_IADEA_XMP3X0);
				break;
			case 'XMP-3250':
			case 'XMP-3350':
			case 'XMP-3450':
			case 'XDS-1950':
			case 'XDS-2450':
				$this->setModelId(PlayerModel::PLAYER_MODEL_IADEA_XMP3X50);
				break;
			case 'fs5-player':
			case 'fs5-playerSTLinux':
			case 'NTnextPlayer':
			case 'Kathrein':
			case 'NT111':
			case 'NTwin':
				$this->setModelId(PlayerModel::PLAYER_MODEL_KATHREIN);
				break;
			case 'XMP-2200':
			case 'MBR-1100':
			case 'XMP-6200':
			case 'XMP-6250':
			case 'XMP-6400':
			case 'XMP-7300':
			case 'XDS-1060':
			case 'XDS-1062':
			case 'XDS-1068':
			case 'XDS-1078':
				$this->setModelId(PlayerModel::PLAYER_MODEL_IADEA_XMP2X00);
				break;
			case 'Garlic':
				$this->setModelId(PlayerModel::PLAYER_MODEL_GARLIC);
				break;
			case 'IDS-App':
				$this->setModelId(PlayerModel::PLAYER_MODEL_IDS);
				break;
			case 'BXP-202':
			case 'BXP-301':
			case 'TD-1050':
				$this->setModelId(PlayerModel::PLAYER_MODEL_QBIC);
				break;
			default:
				$this->setModelId(PlayerModel::PLAYER_MODEL_UNKNOWN);
				break;
		}
		return $this;
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
							   'model' => $this->detectModelId($matches[5])->getModelId()
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
							   'model' => $this->detectModelId($matches[5])->getModelId()
						   ));
			$this->is_ds_player = true;
		}
		elseif (preg_match('/([^ ]+) \(UUID:(.*?)\) (.*?)-(.*?) \(MODEL:(.*?)\)/', $agent_string, $matches))
		{
			$this->setInfo(array(
							   'uuid' => $matches[2],
							   'firmware_version' => $matches[4],
							   'name' => urldecode($matches[3]),
							   'model' => $this->detectModelId($matches[5])->getModelId()
						   ));
			$this->is_ds_player = true;
		}
		else
		{
			$this->is_ds_player = false;
		}
		return $this;
	}

	/**
	 * @param int $model_id
	 *
	 * @return UserAgent
	 */
	protected function setModelId($model_id)
	{
		$this->model_id = $model_id;
		return $this;
	}
	/**
	 * @return int
	 */
	protected function getModelId()
	{
		return $this->model_id;
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