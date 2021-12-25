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


use Garlic\model\BaseFileModel;
use PHPUnit\Framework\TestCase;

class BaseFileModelTest extends TestCase
{
	/**
	 * @var string
	 */
	protected $path_to_test_directory;

	protected function tearDown()
	{
		PHPUnitUtils::deleteRecursive($this->getTestDirectoryPath());
	}

	/**
	 * @group units
	 */
	public function testLastModifiedDateTime()
	{
		$test_file = _ResourcesPath.'/media/image2.jpg';

		$BaseModel = new BaseFileModel();
		$this->expectException(RuntimeException::class);
		$this->expectExceptionMessage("File '".$test_file."' not readable");

		$this->assertTrue(strlen($BaseModel->lastModifiedDateTime($test_file)) == 19); // 0000-00-00 00:00:00 => 19 characters

	}


	/**
	 * @group units
	 */
	public function testLastModifiedDateTimeFailed()
	{
		$test_file = $this->getTestDirectoryPath().'/nothing.txt';

		$BaseModel = new BaseFileModel();
		$this->expectException(RuntimeException::class);
		$this->expectExceptionMessage("File '".$test_file."' not readable");

		$BaseModel->lastModifiedDateTime($test_file);

	}

	/**
	 * @group units
	 */
	public function testSaveFile()
	{
		$test_file = $this->getTestDirectoryPath().'/test_file';

		$BaseModel = new BaseFileModel();
		$Method = PHPUnitUtils::getProtectedMethod($BaseModel, 'saveFile');
		$Method->invoke($BaseModel, $test_file, 'dummy');
		$this->assertTrue(file_exists($test_file));
		$this->assertEquals('dummy', file_get_contents($test_file));
	}

	/**
	 * @group units
	 */
	public function testSaveFileFailed()
	{
		$test_file = '/etc/test_file';

		$BaseModel = new BaseFileModel();
		$Method = PHPUnitUtils::getProtectedMethod($BaseModel, 'saveFile');

		$this->expectException(RuntimeException::class);
		$this->expectExceptionMessage('Can not create ' . $test_file);

		$Method->invoke($BaseModel, $test_file, 'dummy');
		$this->assertFalse(file_exists($test_file));
	}

	/**
	 * @group units
	 */
	public function testReadContentofFile()
	{
		$test_file = $this->getTestDirectoryPath().'/test_file';
		$expected  = 'dummy text';
		file_put_contents($test_file, $expected);

		$BaseModel = new BaseFileModel();
		$Method   = PHPUnitUtils::getProtectedMethod($BaseModel, 'readContentofFile');
		$returned = $Method->invoke($BaseModel, $test_file);
		$this->assertEquals($expected, $returned);
	}

	/**
	 * @group units
	 */
	public function testReadContentofFileFailed()
	{
		$test_file = 'not_existing_file';

		$BaseModel = new BaseFileModel();
		$Method = PHPUnitUtils::getProtectedMethod($BaseModel, 'readContentofFile');

		$this->expectException(RuntimeException::class);
		$this->expectExceptionMessage($test_file.' not exists');

		$Method->invoke($BaseModel, $test_file, '');
	}

	// ===================== helper methods =============================================================================

	/**
	 * @return string
	 */
	protected function getTestDirectoryPath()
	{
		if (is_null($this->path_to_test_directory))
		{
			$this->path_to_test_directory = _ResourcesPath.'/directory_tests';
		}

		return $this->path_to_test_directory;
	}

}
