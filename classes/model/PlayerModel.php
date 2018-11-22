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

namespace Basil\model;


class PlayerModel extends BaseFileModel
{
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
	 * @param string $uuid
	 *
	 * @return bool
	 */
	public function isRegistered($uuid)
	{
		return file_exists($this->player_register_path.'/'.$uuid.'.reg');
	}

	/**
	 * @param string $uuid
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
	 * @param string $uuid
	 * @param string $agent_string
	 *
	 * @return $this
	 */
	public function register($uuid, $agent_string)
	{
		$path = $this->player_register_path.'/'.$uuid.'.reg';
		$this->saveFile($path, $agent_string);
		return $this;
	}

	/**
	 * @param string $uuid
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function load($uuid)
	{
		$filepath = $this->player_register_path.'/'.$uuid.'.reg';
		return $this->readContentofFile($filepath);
	}

	/**
	 * @return \DirectoryIterator
	 */
	public function scanRegisteredPlayer()
	{
		return new \DirectoryIterator($this->player_register_path);
	}

}