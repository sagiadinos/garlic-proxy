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


use Garlic\model\Directory;
use PHPUnit\Framework\TestCase;

class DirectoryTest extends TestCase
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
	public function testCreateDirectoryIfNotExist()
	{
		$test_dir  =  $this->getTestDirectoryPath() . '/test';
		$Directory = new Directory();
		$this->assertFalse(file_exists($test_dir));
		$Directory->createDirectoryIfNotExist($test_dir);
		$this->assertTrue(file_exists($test_dir));

	}

	/**
	 * @group units
	 */
	public function testCreateDirectoryIfNotExistFailCauseFile()
	{
		$test_dir  =  $this->getTestDirectoryPath() . '/test';
		file_put_contents($test_dir, 'dummy');
		$Directory = new Directory();
		$this->expectException(RuntimeException::class);
		$this->expectExceptionMessage('Directory of ' . $test_dir . ' can not created, because a file with this name already exists');
		$Directory->createDirectoryIfNotExist($test_dir);
	}

	/**
	 * @group units
	 */
	public function testCreateDirectoryIfNotExistFailCauseNotReadable()
	{
		$test_dir  =  '/root';
		$Directory = new Directory();
		$this->expectException(RuntimeException::class);
		$this->expectExceptionMessage('Directory of ' . $test_dir . ' is not readable');
		$Directory->createDirectoryIfNotExist($test_dir);
	}

	/**
	 * @group units
	 */
	public function testCreateDirectoryIfNotExistFailCauseNotCreatable()
	{
		$test_dir  =  '/etc/uiuiu';
		$Directory = new Directory();
		$this->expectException(RuntimeException::class);
		$this->expectExceptionMessage('Can not create directory ' . $test_dir);
		$Directory->createDirectoryIfNotExist($test_dir);
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
