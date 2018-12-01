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


use Basil\model\PlayerModel;
use PHPUnit\Framework\TestCase;

class PlayerModelTest extends TestCase
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
	public function testIsRegistered()
	{
		$uuid       = 'b8294bat-d28f-51af-f94o-211869af5854';
		$path       = $this->getTestDirectoryPath();

		file_put_contents($path.'/'.$uuid.'.reg', 'Heidewitzka der Kapitän');
		$PlayerModel = new PlayerModel($path);
		$this->assertTrue($PlayerModel->isRegistered($uuid));

		$this->assertFalse($PlayerModel->isRegistered('not-existing-dsjfsjsdkhfds'));
	}

	/**
	 * @group units
	 */
	public function testDelete()
	{
		$uuid       = 'c7184bat-d28f-51af-f94o-211869af5854';
		$path       = $this->getTestDirectoryPath();
		$file_path  = $path.'/'.$uuid.'.reg';
		file_put_contents($file_path, 'Heidewitzka der Kapitän');
		$this->assertTrue(file_exists($file_path));

		$PlayerModel = new PlayerModel($path);
		$PlayerModel->delete($uuid);

		$this->assertFalse(file_exists($file_path));
	}

	/**
	 * @group units
	 */
	public function testRegister()
	{
		$uuid       = 'd6073cat-d28f-51af-f94o-211869af5854';
		$path       = $this->getTestDirectoryPath();
		$file_path  = $path.'/'.$uuid.'.reg';
		$content    = 'this should be an agent string';

		$PlayerModel = new PlayerModel($path);
		$PlayerModel->register($uuid, $content);

		$this->assertTrue(file_exists($file_path));
		$this->assertEquals($content, file_get_contents($file_path));
	}

	/**
	 * @group units
	 */
	public function testLoad()
	{
		$uuid       = 'd6073cat-d28f-51af-f94o-211869af5854';
		$path       = $this->getTestDirectoryPath();
		$file_path  = $path.'/'.$uuid.'.reg';
		$content    = 'this should be another agent string';
		file_put_contents($file_path, $content);

		$PlayerModel = new PlayerModel($path);

		$this->assertEquals($content, $PlayerModel->load($uuid));
	}

	/**
	 * @group units
	 */
	public function testScanRegisteredPlayer()
	{
		$uuids  = array('uuid_1', 'uuid_2', 'uuid_3', 'uuid_4');
		$path   = $this->getTestDirectoryPath();
		foreach ($uuids as $uuid)
		{
			$file_path = $path.'/'.$uuid.'.reg';
			$content = 'this should be some agent strings';
			file_put_contents($file_path, $content);
		}

		$PlayerModel = new PlayerModel($path);
		$player_list = $PlayerModel->scanRegisteredPlayer();
		$i = 0;
		foreach ($player_list as $player)
		{
			if ($player->isFile())
			{
				// we must search in array values for false (means not found) cause
				// A IMHO more elegant compare with $uuids[$i] and $player->getBasename('.reg') will failed
				// cause DirectoryIterator did not read the files in the same order as player_list array implicates
				// uncomment next line to see what I mean
				// echo "\n".$uuids[$i]. '|'.$player->getBasename('.reg');

				$this->assertTrue(array_search($player->getBasename('.reg'), $uuids) !== false);
				$i++;
			}
		}

		$this->assertEquals(count($uuids), $i);
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
