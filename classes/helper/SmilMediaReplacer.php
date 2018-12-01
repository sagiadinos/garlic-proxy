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

use Thymian\framework\Curl;

class SmilMediaReplacer
{

	/**
	 * @var string
	 */
	protected $smil = '';
	/**
	 * @var Curl
	 */
	protected $Curl;
	/**
	 * @var Configuration
	 */
	protected $Configuration;
	/**
	 * @var array
	 */
	protected $matches = array();
	/**
	 * @var string
	 */
	protected $relative_local_filepath = '';
	/**
	 * @var string
	 */
	protected $absolute_local_filepath = '';

	public function __construct($smil_index, Curl $curl, Configuration $config)
	{
		$this->setSmil($smil_index)
			 ->setCurl($curl)
			 ->setConfiguration($config);
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
		// not so easy handle with regex, cause we need to include an ref-tag (widgets use it for example)
		// the string ref also exists as substring in p=>ref<=etch
		// so we use array_unique
		$this->setMatches(array_unique($matches[1]));
		return $this->getMatches();
	}

	public function replace($user_agent)
	{
		foreach ($this->getMatches() as $uri)
		{
			if (strpos($uri, $this->getConfiguration()->getHomeDomain()) === false)
				continue;

			$this->getCurl()->setUserAgent($user_agent);

			$this->determineLocalFilePaths($uri);

			// replace the smil
			if ($this->downloadFile($uri, $this->absolute_local_filepath))
			{
				$this->setSmil(str_replace($uri, $this->relative_local_filepath, $this->getSmil()));
			}
		}
		return $this;
	}

	/**
	 * @param $uri
	 *
	 * @return $this
	 */
	protected function determineLocalFilePaths($uri)
	{
		$md5_uri = $this->buildMd5Uri($uri);

		// check if a md5 file exists on server
		$this->getCurl()
			 ->setUrl($md5_uri)
			 ->curlExec(false)
		;

		$url_path = parse_url($uri, PHP_URL_PATH);

		// if yes, then read the md5 value and build a local path
		if ($this->getCurl()->getHttpCode() == 200)
		{
			$md5             = trim($this->getCurl()->getResponseBody());
			$extension       = pathinfo($url_path, PATHINFO_EXTENSION);
			$this->absolute_local_filepath = $this->getConfiguration()->getFullPathValuesByKey('media_path').'/'.$md5.'.'.$extension;
			$this->relative_local_filepath = $this->getConfiguration()->getPathValuesByKey('media_path').'/'.$md5.'.'.$extension;
		}
		else // if not, then build a local path with filename
		{
			$filename        = pathinfo($url_path, PATHINFO_BASENAME);
			$this->absolute_local_filepath = $this->getConfiguration()->getFullPathValuesByKey('media_path').'/'.$filename;
			$this->relative_local_filepath = $this->getConfiguration()->getPathValuesByKey('media_path').'/'.$filename;
		}
		return $this;
	}

	/**
	 * @param $uri
	 * @param $local_file_path
	 *
	 * @return bool
	 */
	protected function downloadFile($uri, $local_file_path)
	{
		if (file_exists($local_file_path))
		{
			return true;
		}
		$this->getCurl()->setUrl($uri)
			 ->setFileDownload(true)
			 ->setLocalFilepath($local_file_path)
			 ->curlExec(false)
		;

		if (!file_exists($local_file_path))
		{
			return false;
		}

		return true;
	}

	/**
	 * @param $uri
	 *
	 * @return string
	 */
	protected function buildMd5Uri($uri)
	{
		$md5_uri = $uri.'.md5';
		if ($this->isUriRelative($uri))
			$md5_uri = $this->getConfiguration()->getIndexServer().'/'.$md5_uri;
		return $md5_uri;
	}

	/**
	 * @param $uri
	 *
	 * @return bool
	 */
	protected function isUriRelative($uri)
	{
		return ($uri != '/' && strpos($uri,'http') === false);
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
	 * @return Curl
	 */
	protected function getCurl()
	{
		return $this->Curl;
	}

	/**
	 * @param Curl $curl
	 *
	 * @return SmilMediaReplacer
	 */
	protected function setCurl($curl)
	{
		$this->Curl = $curl;
		return $this;
	}

	/**
	 * @return Configuration
	 */
	public function getConfiguration()
	{
		return $this->Configuration;
	}

	/**
	 * @param Configuration $Configuration
	 *
	 * @return SmilMediaReplacer
	 */
	public function setConfiguration($Configuration)
	{
		$this->Configuration = $Configuration;
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