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


use Basil\model\IndexModel;

class IndexModelTest extends \PHPUnit\Framework\TestCase
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
	public function testLastUpdateIndexExists()
	{
		$uuid       = 'has_all_media';
		$path       = _ResourcesPath.'/indexes';
		$IndexModel = new IndexModel($path);
		$datetime   = $IndexModel->lastUpdate($uuid);

		$this->assertTrue($datetime != '0000-00-00 00:00:00');
	}

	/**
	 * @group units
	 */
	public function testLastUpdateIndexNotExists()
	{
		$uuid       = 'no-no-no';
		$path       = 'NoPath';
		$IndexModel = new IndexModel($path);

		$this->assertEquals('0000-00-00 00:00:00', $IndexModel->lastUpdate($uuid));
	}


	/**
	 * @group units
	 */
	public function testSaveIndex()
	{
		$uuid       = 'b8294bat-d28f-51af-f94o-211869af5854';
		$path       = $this->getTestDirectoryPath();
		$IndexModel = new IndexModel($path);
		$IndexModel->saveIndex($uuid, 'blah');

		$this->assertTrue(file_exists($path.'/'.$uuid.'.smil'));
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
