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

/**
 * Class PlayerRegisterModel
 * @package Basil
 */
class PlayerModel
{
	const PLAYER_MODEL_UNKNOWN        = 0;
	const PLAYER_MODEL_IADEA_XMP1X0   = 1; // IAdea 1x0 Series SD Video 1920px images
	const PLAYER_MODEL_IADEA_XMP3X0   = 2; // IAdea 3x0 Series +HD Video
	const PLAYER_MODEL_IADEA_XMP3X50  = 3; // IAdea 3x50 Series +HTML5
	const PLAYER_MODEL_KATHREIN       = 4; // Kathrein with only h264 in ts Container
	const PLAYER_MODEL_IADEA_XMP2X00  = 5; // IAdea 2000, 6000 and 7000 (4K) Android Series with new xml config and SMIL Structure
	const PLAYER_MODEL_GARLIC         = 6; // Sagiadinos open source software player garlic
	const PLAYER_MODEL_IDS            = 7; // Isaria Digital Signage Player
	const PLAYER_MODEL_QBIC           = 8; // QBiC Signage Player

	/**
	 * @var string
	 */
	protected $player_register_path = '';

	public function __construct($path)
	{
		$this->player_register_path = $path;
		$this->createDirectoryIfNotExist($path);
	}

	/**
	 * @param $uuid
	 *
	 * @return bool
	 */
	public function isRegistered($uuid)
	{
		return file_exists($this->player_register_path.'/'.$uuid.'.reg');
	}

	/**
	 * @param $uuid
	 *
	 * @return $this
	 */
	public function delete($uuid)
	{
		if ($this->isRegistered($uuid))
			unlink($this->player_register_path.'/'.$uuid.'.reg');
		return $this;
	}

	/**
	 * @param $uuid
	 * @param $agent_string
	 *
	 * @return $this
	 */
	public function register($uuid, $player_name)
	{
		$path = $this->player_register_path.'/'.$uuid.'.reg';
		file_put_contents($path, $player_name);
		return $this;
	}

	public function scanRegisteredPlayer()
	{
		return new \DirectoryIterator($this->player_register_path);
	}

	protected function createDirectoryIfNotExist($directory)
	{
		if (is_file($directory))
		{
			throw new \Exception('Directory of ' . $directory . ' can not created, because a file with this name already exists');
		}

		if (!is_dir($directory))
		{
			if(!mkdir($directory, 0775, true))
				throw new \Exception('Can not create directory ' . $directory);

			if (!chmod($directory, 0775))
				throw new \Exception('Can not chmod directory ' . $directory . ' to 775');
		}
		else
		{
			if (!is_readable($directory))
				throw new \Exception('Directory of ' . $directory . ' is not readable');
		}

		return $this;
	}

}