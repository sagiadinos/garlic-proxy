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

use Basil\helper\Configuration;
use Thymian\framework\Curl;

/**
 * Concept:
 *
 * files are downloaded from CMS
 * If CMS support md5 hashes, the media files will downloaded and renamed as md5.
 * With this way we can recognize same files even if they had a different names on CMS
 *
 * If CMS not support md5, the files will be downloaded with their original name.
 *
 */
class RemoteFiles
{
	/**
	 * @var Curl
	 */
	protected $Curl;
	/**
	 * @var Configuration
	 */
	protected $Configuration;
	/**
	 * @var string
	 */
	protected $relative_local_filepath = '';
	/**
	 * @var string
	 */
	protected $absolute_local_filepath = '';
	/**
	 * @var string
	 */
	protected $full_uri = '';

	/**
	 * Downloader constructor.
	 */
	public function __construct(Curl $curl, Configuration $config)
	{
		$this->setCurl($curl)
			 ->setConfiguration($config);
	}

	/**
	 * @return string
	 */
	public function getRelativeLocalFilepath()
	{
		return $this->relative_local_filepath;
	}

	/**
	 * @param $uri
	 *
	 * @return bool
	 */
	public function isUriForDownload($uri)
	{
		$this->full_uri = $this->buildFullUri($uri); // concat domain.tld for the case uri is relative

		if (strpos($this->full_uri, $this->getConfiguration()->getHomeDomain()) === false)
		{
			return false;
		}

		return true;
	}

	/**
	 * @return $this
	 */
	public function determineFilePaths()
	{
		$url_path = parse_url($this->full_uri, PHP_URL_PATH);

		// if yes, then read the md5 value and build a local path
		if ($this->hasMd5File())
		{
			$md5             = trim($this->getCurl()->getResponseBody());
			$extension       = pathinfo($url_path, PATHINFO_EXTENSION);
			$this->setLocalFilepaths($md5.'.'.$extension);
		}
		else // if not, then build a local path with filename
		{
			$filename        = pathinfo($url_path, PATHINFO_BASENAME);
			$this->setLocalFilepaths($filename);
		}
		return $this;
	}

	/**
	 * @return bool
	 */
	public function downloadFile()
	{
		if (file_exists($this->absolute_local_filepath))
		{
			return true;
		}

		$this->getCurl()
			 ->setUrl($this->full_uri)
			 ->setFileDownload(true)
			 ->setLocalFilepath($this->absolute_local_filepath)
			 ->curlExec(false)
		;

		if (!file_exists($this->absolute_local_filepath))
		{
			return false;
		}

		return true;
	}

	/**
	 * @return bool
	 */
	protected function hasMd5File()
	{
		$md5_uri = $this->buildMd5Uri($this->full_uri);
		$this->getCurl()
			 ->setUrl($md5_uri)
			 ->curlExec(false)
		;
		return ($this->getCurl()->getHttpCode() == 200);
	}

	/**
	 * @param $filename
	 *
	 * @return $this
	 */
	protected function setLocalFilepaths($filename)
	{
		$this->absolute_local_filepath = $this->getConfiguration()->getFullPathValuesByKey('media_path').'/'.$filename;
		$this->relative_local_filepath = $this->getConfiguration()->getPathValuesByKey('media_path').'/'.$filename;
		return $this;
	}

	/**
	 * @param $uri
	 *
	 * @return string
	 */
	protected function buildMd5Uri($uri)
	{
		return $this->buildFullUri($uri.'.md5');
	}


	/**
	 * @param $uri
	 *
	 * @return string
	 */
	protected function buildFullUri($uri)
	{
		if ($this->isUriRelative($uri))
			$uri = $this->getConfiguration()->getIndexServer().'/'.$uri;
		return $uri;
	}

	/**
	 * @param $uri
	 *
	 * @return bool
	 */
	protected function isUriRelative($uri)
	{
		return ($uri != '/' && strpos($uri,'http' ) === false && strpos($uri,'ftp' ) === false);
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
	 * @return RemoteFiles
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
	 * @return RemoteFiles
	 */
	public function setConfiguration($Configuration)
	{
		$this->Configuration = $Configuration;
		return $this;
	}
}