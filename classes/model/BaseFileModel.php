<?php
/*************************************************************************************
 * basil-proxy: A proxy solution for Digital Signage SMIL Player
 * Copyright (C) 2018 Nikolaos Sagiadinos <ns@smil-control.com>
 * This file is part of the basil-proxy source code
 *
 * This program is free software: you can redistribute it and/or  modify
 * it under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *************************************************************************************/

namespace Basil\model;

class BaseFileModel
{

	/**
	 * @param $filepath
	 *
	 * @return bool
	 */
	public function fileExists($filepath)
	{
		return file_exists($filepath);
	}

	/**
	 * @param $filepath
	 *
	 * @return string
	 * @throws \RuntimeException
	 */
	public function lastModifiedDateTime($filepath)
	{
		$timestamp = @filemtime($filepath);
		if ($timestamp === false)
			throw new \RuntimeException("File '$filepath' not readable");

		return date('Y-m-d H:i:s', $timestamp);
	}

	/**
	 * @param $filepath
	 * @param $content
	 *
	 * @return $this
	 * @throws \Exception
	 */
	protected function saveFile($filepath, $content)
	{
		if (@file_put_contents($filepath, $content) === false)
			throw new \RuntimeException('Can not create ' . $filepath);
		return $this;
	}

	/**
	 * @param $filepath
	 *
	 * @return string
	 * @throws \Exception
	 */
	protected function readContentofFile($filepath)
	{
		if (!file_exists($filepath))
			throw new \RuntimeException($filepath.' not exists');

		return file_get_contents($filepath);
	}
}