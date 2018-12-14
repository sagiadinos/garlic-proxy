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


use Basil\helper\Configuration;
use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
{

	/**
	 * @group units
	 */
	public function testConstructorFailedIni()
	{
		$ini = 'path/not_existing.ini';
		$this->expectException(RuntimeException::class);
		$this->expectExceptionMessage('Config file \''.$ini.'\' not exists');
		$Helper = new Configuration($ini, './systemdir');
	}

	/**
	 * @group units
	 */
	public function testConstructorFailedSystemDir()
	{
		$ini = _ResourcesPath.'/configuration/main.ini';
		$this->expectException(RuntimeException::class);
		$this->expectExceptionMessage('System_dir cannot be empty');
		$Helper = new Configuration($ini, '');
	}

	/**
	 * @group units
	 */
	public function testGetIndexServer()
	{
		$ini = _ResourcesPath.'/configuration/main.ini';
		$Helper = new Configuration($ini, './systemdir');

		$this->assertEquals('http://localhost', $Helper->getIndexServer());
	}

	/**
	 * @group units
	 */
	public function testGetIndexServerFailNoScheme()
	{
		$ini = _ResourcesPath.'/configuration/no_scheme.ini';
		$Helper = new Configuration($ini, './systemdir');

		$this->expectException(RuntimeException::class);
		$this->expectExceptionMessage('No Scheme (http/https) in index_server_uri found');
		$Helper->getIndexServer();
	}

	/**
	 * @group units
	 */
	public function testGetIndexServerUri()
	{
		$ini = _ResourcesPath.'/configuration/main.ini';
		$Helper = new Configuration($ini, './systemdir');

		$this->assertEquals('http://localhost/some/path/index.smil', $Helper->getIndexServerUri());
	}


	/**
	 * @group units
	 */
	public function testGetIndexServerUriFail()
	{
		$ini = _ResourcesPath.'/configuration/empty.ini';
		$Helper = new Configuration($ini, './systemdir');

		$this->expectException(RuntimeException::class);
		$this->expectExceptionMessage('Key index_server_uri not exists in config');
		$Helper->getIndexServer();
	}

	/**
	 * @group units
	 */
	public function testHomeDomain()
	{
		$ini = _ResourcesPath.'/configuration/main.ini';
		$Helper = new Configuration($ini, './systemdir');

		$this->assertEquals('basil-test.dev', $Helper->getHomeDomain());
	}

	/**
	 * @group units
	 */
	public function testGetHomeDomainFail()
	{
		$ini = _ResourcesPath.'/configuration/empty.ini';
		$Helper = new Configuration($ini, './systemdir');

		$this->expectException(RuntimeException::class);
		$this->expectExceptionMessage('Key home_domain not exists in config');
		$Helper->getHomeDomain();
	}



	/**
	 * @group units
	 */
	public function testGetFullPathValuesByKey()
	{
		$ini = _ResourcesPath.'/configuration/main.ini';
		$Helper = new Configuration($ini, './systemdir');

		$this->assertEquals('./systemdir/www/var/player', $Helper->getFullPathValuesByKey('player_path'));
		$this->assertEquals('./systemdir/www/var/indexes', $Helper->getFullPathValuesByKey('index_path'));
		$this->assertEquals('./systemdir/www/var/media', $Helper->getFullPathValuesByKey('media_path'));
		$this->assertEquals('./systemdir/www/resources', $Helper->getFullPathValuesByKey('resources_path'));
	}

	/**
	 * @group units
	 */
	public function testGetPathValuesByKey()
	{
		$ini = _ResourcesPath.'/configuration/main.ini';
		$Helper = new Configuration($ini, './systemdir');

		$this->assertEquals('var/player', $Helper->getPathValuesByKey('player_path'));
		$this->assertEquals('var/indexes', $Helper->getPathValuesByKey('index_path'));
		$this->assertEquals('var/media', $Helper->getPathValuesByKey('media_path'));
		$this->assertEquals('resources', $Helper->getPathValuesByKey('resources_path'));
	}

	/**
	 * @group units
	 */
	public function testGetPathValuesByKeyFail()
	{
		$ini = _ResourcesPath.'/configuration/main.ini';
		$Helper = new Configuration($ini, './systemdir');

		$this->expectException(RuntimeException::class);
		$this->expectExceptionMessage('not_existing_key not exists');
		$Helper->getPathValuesByKey('not_existing_key');
	}
}
