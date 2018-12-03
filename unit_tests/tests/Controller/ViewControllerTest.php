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


use Basil\controller\ViewController;
use PHPUnit\Framework\TestCase;

class ViewControllerTest extends TestCase
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
	public function testView()
	{
		$Controller = $this->initMockAllConstructorInjections();

		$this->PlayerModelMock->expects($this->once())->method('scanRegisteredPlayer');
		$this->ConfigMock->expects($this->once())->method('getSystemDir')->willReturn(_ResourcesPath);
		ob_start();
		$Controller->view();
		$returned = ob_get_clean();
		$expected = 'I am the header.phpI am the list.phpI am the footer.php';
		$this->assertEquals($expected, $returned);
	}

	/**
	 * @group units
	 */
	public function testViewFailed()
	{
		$Controller = $this->initMockAllConstructorInjections();

		$this->PlayerModelMock->expects($this->once())->method('scanRegisteredPlayer');
		$this->ConfigMock->expects($this->once())->method('getSystemDir')->willReturn('');

		$this->expectException(RuntimeException::class);
		$this->expectExceptionMessage('/view/notExists.php not found');

		$Controller->view('notExists');
	}


	// ===================== helper methods =============================================================================

	/**
	 * @return ViewController
	 */
	protected function initMockAllConstructorInjections()
	{
		$this->PlayerModelMock = $this->createMock('Basil\model\PlayerModel');
		$this->ConfigMock      = $this->createMock('Basil\helper\Configuration');

		return new ViewController($this->PlayerModelMock, $this->ConfigMock);

	}

}
