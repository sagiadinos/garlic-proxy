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


class Directory
{
	/**
	 * @param string $directory
	 *
	 * @return $this
	 * @throws \Exception
	 */
	public function createDirectoryIfNotExist($directory)
	{
		if (is_file($directory))
		{
			throw new \RuntimeException('Directory of ' . $directory . ' can not created, because a file with this name already exists');
		}

		if (!is_dir($directory))
		{
			$this->create($directory);
		}
		else
		{
			if (!is_readable($directory))
				throw new \RuntimeException('Directory of ' . $directory . ' is not readable');
		}

		return $this;
	}

	/**
	 * @param $directory
	 *
	 * @return $this
	 * @throws \Exception
	 */
	protected function create($directory)
	{
		if(!@mkdir($directory, 0775, true))
			throw new \RuntimeException('Can not create directory ' . $directory);

		// AFAIK it should be not possible that this failed after a create
		chmod($directory, 0775);
		return $this;
	}

}