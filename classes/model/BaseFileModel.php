<?php
/*************************************************************************************
 * basil-proxy: A proxy solution for Digital Signage SMIL Player
 * Copyright (C) 2018 Nikolaos Saghiadinos <ns@smil-control.com>
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
	 * @param $content
	 *
	 * @return $this
	 * @throws \Exception
	 */
	protected function saveFile($filepath, $content)
	{
		if (file_put_contents($filepath, $content) === false)
			throw new \Exception('Can not create ' . $filepath);
		return $this;
	}

	/**
	 * @param string $directory
	 *
	 * @return $this
	 * @throws \Exception
	 */
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