<?php
/*************************************************************************************
garlic-proxy: A proxy solution for Digital Signage SMIL Player
Copyright (C) 2021 Nikolaos Sagiadinos <ns@smil-control.com>
This file is part of the garlic-proxy source code
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

namespace Garlic\controller;

use Garlic\model\IndexModel;
use Garlic\model\PlayerModel;
use Thymian\framework\Curl;
use Garlic\helper\Configuration;

class SmilIndexController extends BaseController
{
	/**
	 * @var Curl
	 */
	protected $Curl;
	/**
	 * @var bool
	 */
	protected $new_index = false;

	/**
	 * SmilIndexController constructor.
	 *
	 * @param PlayerModel   $playerModel
	 * @param Configuration $config
	 * @param Curl          $Curl
	 */
	function __construct(PlayerModel $playerModel, Configuration $config, Curl $Curl)
	{
		$this->setCurl($Curl)
			 ->setConfiguration($config);

		$Curl->setUrl($this->getConfiguration()->getIndexServerUri());
		parent::__construct($playerModel, $config);
	}

	/**
	 * @return string
	 */
	public function readDownloadedIndex()
	{
		return $this->getCurl()->getResponseBody();
	}

	/**
	 * @return $this
	 */
	public function requestIndexForRegisteredPlayer($uuid)
	{
		$this->new_index = false;
		$this->getCurl()
			 ->clearHeaders()
			 ->setSplitHeaders(false)
			 ->setUserAgent($this->determineUserAgent($uuid));

		$this->requestHead();
		if ($this->getCurl()->getHttpCode() == 200)
		{
			$this->requestGet();
			$this->new_index = true;
		}
		return $this;
	}

	public function isNewIndex()
	{
		return $this->new_index;
	}

	/**
	 * @return string
	 */
	protected function determineUserAgent($uuid)
	{
		$user_agent = $this->getModel()->load($uuid);
		return $user_agent;
	}

	/**
	 * @return $this
	 */
	protected function requestHead()
	{
		$this->getCurl()
			 ->setRequestMethodHead()
			 ->curlExec();
		return $this;
	}

	/**
	 * @return $this
	 */
	protected function requestGet()
	{
		$this->getCurl()
			 ->setRequestMethodGet()
		     ->curlExec(false);
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
	 * @param $Curl
	 *
	 * @return $this
	 */
	protected function setCurl($Curl)
	{
		$this->Curl = $Curl;
		return $this;
	}


}