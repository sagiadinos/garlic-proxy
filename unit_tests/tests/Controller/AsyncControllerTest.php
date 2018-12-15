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


use Basil\controller\AsyncController;

class AsyncControllerTest extends \PHPUnit\Framework\TestCase
{
	/**
	 * @var \Basil\model\PlayerModel
	 */
	protected $PlayerModelMock;
	protected $ConfigMock;

	/**
	 *  unset all Mocks
	 */
	protected function tearDown()
	{
		unset($this->PlayerModelMock);
		unset($this->ConfigMock);
	}

	/**
	 * @group units
	 */
	public function testSiteWithOutUuid()
	{
		$Controller = $this->initMockAllConstructorInjections();
		$this->ConfigMock->expects($this->once())->method('getSystemDir')->willReturn(_ResourcesPath);

		ob_start();
		$Controller->site();
		$returned = ob_get_clean();
		$expected = 'I am the get_index.php';
		$this->assertEquals($expected, $returned);
	}


	/**
	 * @group units
	 */
	public function testSiteFailed()
	{
		$Controller = $this->initMockAllConstructorInjections();

		$this->ConfigMock->expects($this->once())->method('getSystemDir')->willReturn('');

		$this->expectException(RuntimeException::class);
		$this->expectExceptionMessage('/view/async/notExists.php not found');

		$Controller->site('notExists');
	}


	// ===================== helper methods =============================================================================

	/**
	 * @return AsyncController
	 */
	protected function initMockAllConstructorInjections()
	{
		$this->PlayerModelMock = $this->createMock('Basil\model\PlayerModel');
		$this->ConfigMock      = $this->createMock('Basil\helper\Configuration');

		return new AsyncController($this->PlayerModelMock, $this->ConfigMock);

	}

}
