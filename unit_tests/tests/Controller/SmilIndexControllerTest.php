<?php
/*************************************************************************************
 * garlic-proxy: A proxy solution for Digital Signage SMIL Player
 * Copyright (C) 2021 Nikolaos Sagiadinos <ns@smil-control.com>
 * This file is part of the garlic-proxy source code
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


use Garlic\controller\SmilIndexController;
use PHPUnit\Framework\TestCase;

class SmilIndexControllerTest extends TestCase
{
	/**
	 * @var \Garlic\model\PlayerModel
	 */
	protected $PlayerModelMock;
	protected $ConfigMock;
	protected $CurlMock;

	/**
	 *  unset all Mocks
	 */
	protected function tearDown()
	{
		unset($this->PlayerModelMock);
		unset($this->ConfigMock);
		unset($this->CurlMock);
	}

	/**
	 * @group units
	 */
	public function testReadDownloadedIndex()
	{
		$Controller = $this->initMockAllConstructorInjections();
		$expected   = 'This is the downloaded content';
		$this->CurlMock->expects($this->once())->method('getResponseBody')->willReturn($expected);
		$returned = $Controller->readDownloadedIndex();
		$this->assertEquals($expected, $returned);

	}

	/**
	 * @group units
	 */
	public function testRequestIndexForRegisteredPlayer()
	{
		$Controller = $this->initMockAllConstructorInjections();
		$this->CurlMock->expects($this->once())->method('clearHeaders')->willReturn($this->CurlMock);
		$this->CurlMock->expects($this->once())->method('setSplitHeaders')->willReturn($this->CurlMock);
		$this->CurlMock->expects($this->once())->method('setRequestMethodHead')->willReturn($this->CurlMock);
		$this->CurlMock->expects($this->once())->method('setRequestMethodget')->willReturn($this->CurlMock);

		$this->CurlMock->expects($this->once())->method('getHttpCode')->willReturn(200);
		$Controller->requestIndexForRegisteredPlayer('an_uuid');
		$this->assertTrue($Controller->isNewIndex());
	}

	/**
	 * @group units
	 */
	public function testRequestIndexForRegisteredPlayerUnequal200()
	{
		$Controller = $this->initMockAllConstructorInjections();
		$this->CurlMock->expects($this->once())->method('clearHeaders')->willReturn($this->CurlMock);
		$this->CurlMock->expects($this->once())->method('setSplitHeaders')->willReturn($this->CurlMock);
		$this->CurlMock->expects($this->once())->method('setRequestMethodHead')->willReturn($this->CurlMock);
		$this->CurlMock->expects($this->never())->method('setRequestMethodGet')->willReturn($this->CurlMock);

		$this->CurlMock->expects($this->once())->method('getHttpCode')->willReturn(500);
		$Controller->requestIndexForRegisteredPlayer('an_uuid');
		$this->assertFalse($Controller->isNewIndex());
	}


	// ===================== helper methods =============================================================================

	/**
	 * @return SmilIndexController
	 */
	protected function initMockAllConstructorInjections()
	{
		$this->PlayerModelMock = $this->createMock('Garlic\model\PlayerModel');
		$this->ConfigMock      = $this->createMock('Garlic\helper\Configuration');
		$this->CurlMock        = $this->createMock('Thymian\framework\Curl');

		return new SmilIndexController($this->PlayerModelMock, $this->ConfigMock, $this->CurlMock);

	}

}
