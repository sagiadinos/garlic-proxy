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
	 * @var array
	 */
	protected $matches = array();

	public function __construct($smil_index, Curl $curl)
	{
		$this->setSmil($smil_index)
			 ->setCurl($curl);
	}

	/**
	 * @return string
	 */
	public function getSmil()
	{
		return $this->smil;
	}

	public function replace()
	{
		foreach ($this->getMatches() as $uri)
		{
			if ($this->isUriRelative())
			{
				// add get contenturl
			}
		//	else if (strpos($uri, $home_domain))

			// if uri points to registered domain

			// if uri
		}
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