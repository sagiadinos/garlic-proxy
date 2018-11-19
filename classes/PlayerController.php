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


class PlayerController extends BaseController
{
	protected $UserAgent;

	public function __construct(PlayerModel $model, Configuration $config, UserAgent $user_agent)
	{
		parent::__construct($model, $config);

		$this->setUserAgent($user_agent);
	}

	/**
	 * @return UserAgent
	 */
	public function getUserAgent()
	{
		return $this->UserAgent;
	}

	/**
	 * @param mixed $UserAgent
	 *
	 * @return PlayerController
	 */
	public function setUserAgent($UserAgent)
	{
		$this->UserAgent = $UserAgent;
		return $this;
	}

	public function dispatch()
	{
		if ($this->getModel()->isRegistered($this->getUserAgent()->getUuid()))
		{
			$this->sendSmilIndex();
		}
		else
		{
			$this->registerPlayer();
		}
		return $this;
	}

	protected function registerPlayer()
	{
		$this->getModel()->register($this->getUserAgent()->getUuid(), $this->getUserAgent()->getAgentString());
		return $this;
	}

	protected function sendSmilIndex()
	{
		$file_path = $this->getConfiguration()->getFullPathValuesByKey('index_path').'/uuid.smil';
		if (!file_exists($file_path))
			$file_path = $this->getConfiguration()->getSystemPath().'/www/resources/smil/wait.smil';

		// not cached or cache outdated, 200 OK send index.smil
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($file_path)) . ' GMT', true, 200);
		header('Content-Length: ' . filesize($file_path));
		header("Content-Type: application/smil");
		header("Content-Description: File Transfer");
		header("Content-Disposition: attachment; filename=" . basename($file_path));
		readfile($file_path);
	}

}