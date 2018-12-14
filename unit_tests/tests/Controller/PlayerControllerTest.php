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


use Basil\controller\PlayerController;
use PHPUnit\Framework\TestCase;

class PlayerControllerTest extends TestCase
{

	protected $PlayerModelMock;
	protected $UserAgentMock;
	protected $ConfigMock;

	/**
	 *  unset all Mocks
	 */
	protected function tearDown()
	{
		unset($this->PlayerModelMock);
		unset($this->UserAgentMock);
		unset($this->ConfigMock);
	}

	/**
	 * @group units
	 */
	public function testDispatchForRegisterPlayer()
	{
		$Controller = $this->initMockAllConstructorInjections();
		$this->PlayerModelMock->expects($this->once())->method('isRegistered')->willReturn(false);
		$this->PlayerModelMock->expects($this->once())->method('register');
		$this->UserAgentMock->expects($this->exactly(3))->method('getUuid')->willReturn('not_exist');
		$this->ConfigMock->expects($this->exactly(2))->method('getFullPathValuesByKey')->willReturn('resources'); // second call is relevat
		ob_start();
		$Controller->dispatch();
		$send_smil = ob_get_clean();
		$this->assertEquals(file_get_contents(_TestLibPath.'/../www/resources/smil/wait.smil'), $send_smil);
	}

	/**
	 * @group units
	 */
	public function testDispatchForExistingIndex()
	{
		$Controller = $this->initMockAllConstructorInjections();
		$this->PlayerModelMock->expects($this->once())->method('isRegistered')->willReturn(true);
		$this->PlayerModelMock->expects($this->never())->method('register');
		$this->ConfigMock->expects($this->once())->method('getFullPathValuesByKey')->willReturn(_ResourcesPath.'/indexes');
		$this->UserAgentMock->expects($this->exactly(2))->method('getUuid')->willReturn('has_all_media');
		ob_start();
		$Controller->dispatch();
		$send_smil = ob_get_clean();
		$this->assertEquals(file_get_contents((_ResourcesPath.'/indexes/has_all_media.smil')), $send_smil);
	}

	/**
	 * @group units
	 */
	public function testDispatchForNotExistingIndex()
	{
		$Controller = $this->initMockAllConstructorInjections();
		$this->PlayerModelMock->expects($this->once())->method('isRegistered')->willReturn(true);
		$this->PlayerModelMock->expects($this->never())->method('register');
		// no need for a map cause first value should fail so or so.
		$this->ConfigMock->expects($this->exactly(2))->method('getFullPathValuesByKey')->willReturn(_TestLibPath.'/resources');
		$this->UserAgentMock->expects($this->exactly(2))->method('getUuid')->willReturn('not_existing_uid');

		ob_start();
		$Controller->dispatch();
		$send_smil = ob_get_clean();
		$this->assertEquals(file_get_contents((_TestLibPath.'/../www/resources/smil/wait.smil')), $send_smil);
	}

	// ===================== helper methods =============================================================================

	/**
	 * @return PlayerController
	 */
	protected function initMockAllConstructorInjections()
	{
		$this->PlayerModelMock = $this->createMock('Basil\model\PlayerModel');
		$this->UserAgentMock   = $this->createMock('\Basil\helper\UserAgent');
		$this->ConfigMock      = $this->createMock('Basil\helper\Configuration');

		return new PlayerController($this->PlayerModelMock, $this->ConfigMock, $this->UserAgentMock);

	}

}
