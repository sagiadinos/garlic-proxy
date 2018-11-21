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

use Basil\model\PlayerModel;
use Thymian\framework\Curl;
use Basil\helper\Configuration;

class SmilIndexController extends BaseController
{
	/**
	 * @var
	 */
	protected $Curl;

	/**
	 * SmilIndexController constructor.
	 *
	 * @param PlayerModel   $playerModel
	 * @param Configuration $config
	 * @param Curl          $Curl
	 */
	function __construct(PlayerModel $playerModel, Configuration $config, \Thymian\framework\Curl $Curl)
	{
		$this->setCurl($Curl);
		$Curl->setUrl($this->getConfiguration()->getIndexServer());

		parent::__construct($playerModel, $config);
	}

	/**
	 * @return Curl
	 */
	public function getCurl()
	{
		return $this->Curl;
	}

	/**
	 * @param $Curl
	 *
	 * @return $this
	 */
	public function setCurl($Curl)
	{
		$this->Curl = $Curl;
		return $this;
	}

	/**
	 * @return $this
	 */
	public function downloadIndex($filepath)
	{
		$this->buildUserAgent($filepath);
		$this->requestHead();
		if ($this->getCurl()->getHttpCode() == 200)
		{
			$this->requestGet();
		}
		return $this;
	}

	/**
	 * @return string
	 */
	protected function buildUserAgent($filepath)
	{
		$user_agent = $this->getModel()->getContentOfFile($filepath);
		$this->getCurl()->addHeader($user_agent);
		return $user_agent;
	}

	/**
	 * @return $this
	 */
	protected function requestHead()
	{
		$this->getCurl()->setRequestMethodHead();
		$this->getCurl()->curlExec();
		return $this;
	}

	/**
	 * @return $this
	 */
	protected function requestGet()
	{
		$this->getCurl()->setRequestMethodGet();
		$this->getCurl()->curlExec();
		return $this;
	}

}