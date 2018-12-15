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

namespace Basil\helper;

use Basil\model\RemoteFiles;


class SmilMediaReplacer
{

	/**
	 * @var string
	 */
	protected $smil = '';
	/**
	 * @var array
	 */
	protected $matches = array();

	/**
	 * SmilMediaReplacer constructor.
	 *
	 * @param string $smil_index
	 */
	public function __construct($smil_index)
	{
		$this->setSmil($smil_index);
	}

	/**
	 * @return string
	 */
	public function getSmil()
	{
		return $this->smil;
	}

	/**
	 * @return array
	 */
	public function findMatches()
	{
		preg_match_all('/src\s*=\s*"(.+?)"/', $this->getSmil(), $matches);
		// we must eliminate eventually double matches cause of prefetch-Tag
		// This is complicated to handle with regex, cause we need to include an ref-tag (widgets use it for example)
		// the string ref also exists as substring in p=>ref<=etch
		// so we use a more simple regex with array_unique
		$this->setMatches(array_unique($matches[1]));
		return $this->getMatches();
	}

	/**
	 * @param RemoteFiles $RemoteFiles
	 *
	 * @return $this
	 */
	public function replace(RemoteFiles $RemoteFiles)
	{
		$matches = $this->getMatches();
		foreach ($matches as $uri)
		{
			if (!$RemoteFiles->isUriForDownload($uri))
				continue;

			$RemoteFiles->determineFilePaths();

			if ($RemoteFiles->downloadFile())
			{
				$this->setSmil(str_replace($uri, $RemoteFiles->getRelativeLocalFilepath(), $this->getSmil()));
			}
		}
		return $this;
	}

	/**
	 * @param string $smil
	 *
	 * @return SmilMediaReplacer
	 */
	protected function setSmil($smil)
	{
		$this->smil = $smil;
		return $this;
	}

	/**
	 * @return array
	 */
	protected function getMatches()
	{
		return $this->matches;
	}

	/**
	 * @param array $matches
	 *
	 * @return SmilMediaReplacer
	 */
	protected function setMatches($matches)
	{
		$this->matches = $matches;
		return $this;
	}

}