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


use Basil\model\RemoteFiles;
use PHPUnit\Framework\TestCase;

class RemoteFilesTest extends TestCase
{

	protected $CurlMock;
	protected $ConfigMock;
	/**
	 *  unset all Mocks
	 */
	protected function tearDown()
	{
		unset($this->CurlMock);
		unset($this->ConfigMock);
	}

	/**
	 * @group units
	 */
	public function testIsUriForDownload()
	{
		$Model = $this->initMockAllConstructorInjections();
		$this->ConfigMock->expects($this->once())->method('getHomeDomain')->willReturn('Mydomain.tld');

		$uri   = 'https://Mydomain.tld/var/smil/playlists/3/image.jpg';
		$this->assertTrue($Model->isUriForDownload($uri));
	}

	/**
	 * @group units
	 */
	public function testIsUriForDownloadReturnFalse()
	{
		$Model = $this->initMockAllConstructorInjections();
		$this->ConfigMock->expects($this->once())->method('getHomeDomain')->willReturn('Mydomain.tld');

		$uri   = 'https://ExternDomain.tld/var/smil/playlists/3/image.jpg';
		$this->assertFalse($Model->isUriForDownload($uri));
	}


	/**
	 * @group units
	 */
	public function testDetermineFilePathsWithMd5File()
	{
		$Model = $this->initMockAllConstructorInjections();
		$uri   = 'https://Mydomain.tld/var/smil/playlists/3/image.jpg';
		$Model->isUriForDownload($uri);

		$this->CurlMock->expects($this->once())->method('setUrl')->willReturn($this->CurlMock);
		$this->CurlMock->expects($this->once())->method('curlExec');
		$this->CurlMock->expects($this->once())->method('getResponseBody')->willReturn('a-md5-value');
		$this->CurlMock->expects($this->once())->method('getHttpCode')->willReturn(200);

		$this->ConfigMock->expects($this->once())->method('getFullPathValuesByKey')->willReturn('/www/media/path');
		$this->ConfigMock->expects($this->once())->method('getPathValuesByKey')->willReturn('media/path');

		$Model->determineFilePaths();
		$expected = 'media/path/a-md5-value.jpg';
		$this->assertEquals($expected, $Model->getRelativeLocalFilepath());

		$expected = '/www/media/path/a-md5-value.jpg';
		$result   = \PHPUnitUtils::getProtectedProperty($Model, 'absolute_local_filepath');
		$this->assertEquals($expected, $result->getValue($Model));
	}

	/**
	 * @group units
	 */
	public function testDetermineFilePathsWithOutMd5File()
	{
		$Model = $this->initMockAllConstructorInjections();
		$uri   = 'https://Mydomain.tld/var/smil/playlists/3/image.jpg';
		$Model->isUriForDownload($uri);

		$this->CurlMock->expects($this->once())->method('setUrl')->willReturn($this->CurlMock);
		$this->CurlMock->expects($this->once())->method('curlExec');
		$this->CurlMock->expects($this->never())->method('getResponseBody');
		$this->CurlMock->expects($this->once())->method('getHttpCode')->willReturn(404);

		$this->ConfigMock->expects($this->once())->method('getFullPathValuesByKey')->willReturn('/www/media/path');
		$this->ConfigMock->expects($this->once())->method('getPathValuesByKey')->willReturn('media/path');

		$Model->determineFilePaths();
		$expected = 'media/path/image.jpg';
		$this->assertEquals($expected, $Model->getRelativeLocalFilepath());

		$expected = '/www/media/path/image.jpg';
		$result   = \PHPUnitUtils::getProtectedProperty($Model, 'absolute_local_filepath');
		$this->assertEquals($expected, $result->getValue($Model));
	}

	/**
	 * @group units
	 */
	public function testDownloadFileWhenExists()
	{
		$Model = $this->initMockAllConstructorInjections();
		$uri   = 'https://Mydomain.tld/var/smil/playlists/3/image.jpg';
		$Model->isUriForDownload($uri);

		$this->CurlMock->expects($this->never())->method('setUrl');
		$this->CurlMock->expects($this->never())->method('setFileDownload');
		$this->CurlMock->expects($this->never())->method('setLocalFilepath');
		$this->CurlMock->expects($this->never())->method('curlExec');

		$expected = '/www/media/path/image.jpg';
		$result   = \PHPUnitUtils::getProtectedProperty($Model, 'absolute_local_filepath');
		$result->setValue($Model, _ResourcesPath.'/media/image1.jpg');
		$this->assertTrue($Model->downloadFile());
	}

	/**
	 * @group units
	 */
	public function testDownloadFileNotExistsFailed()
	{
		$Model = $this->initMockAllConstructorInjections();
		$uri   = 'https://Mydomain.tld/var/smil/playlists/3/image.jpg';
		$Model->isUriForDownload($uri);

		$this->CurlMock->expects($this->once())->method('setUrl')->willReturn($this->CurlMock);
		$this->CurlMock->expects($this->once())->method('setFileDownload')->willReturn($this->CurlMock);
		$this->CurlMock->expects($this->once())->method('setLocalFilepath')->willReturn($this->CurlMock);
		$this->CurlMock->expects($this->once())->method('curlExec');

		$expected = '/www/media/path/image.jpg';
		$result   = \PHPUnitUtils::getProtectedProperty($Model, 'absolute_local_filepath');
		$result->setValue($Model, _ResourcesPath.'media/not_there.jpg');

		$this->assertFalse($Model->downloadFile());
	}

	/**
	 * @group units
	 */
	public function testBuildMd5()
	{
		$Model = $this->initMockAllConstructorInjections();

		$method = \PHPUnitUtils::getProtectedMethod($Model, 'buildMd5Uri');
		$this->ConfigMock->expects($this->once())->method('getIndexServer')->willReturn('http://adomain.tld');

		$expected = 'http://adomain.tld/var/smil/playlists/3/video.mkv.md5';

		$this->assertEquals($expected, $method->invoke($Model, 'var/smil/playlists/3/video.mkv'));
	}


	/**
	 * @group units
	 */
	public function testBuildFullUriWithRelative()
	{
		$Model = $this->initMockAllConstructorInjections();

		$method = \PHPUnitUtils::getProtectedMethod($Model, 'buildFullUri');
		$this->ConfigMock->expects($this->once())->method('getIndexServer')->willReturn('http://domain.tld');

		$expected = 'http://domain.tld/var/smil/playlists/3/video.mkv';

		$this->assertEquals($expected, $method->invoke($Model, 'var/smil/playlists/3/video.mkv'));
	}

	/**
	 * @group units
	 */
	public function testBuildFullUriWithAbsolute()
	{
		$Model = $this->initMockAllConstructorInjections();

		$method = \PHPUnitUtils::getProtectedMethod($Model, 'buildFullUri');
		$this->ConfigMock->expects($this->never())->method('getIndexServer');

		$expected = 'http://domain2.tld/var/smil/playlists/3/video2.mkv';

		$this->assertEquals($expected, $method->invoke($Model, 'http://domain2.tld/var/smil/playlists/3/video2.mkv'));
	}

	/**
	 * @group units
	 */
	public function testIsUriRelative()
	{
		$Model = $this->initMockAllConstructorInjections();

		$method = \PHPUnitUtils::getProtectedMethod($Model, 'isUriRelative');

		$this->assertTrue($method->invoke($Model, 'var/smil/playlists/3/video.mkv'));
		$this->assertTrue($method->invoke($Model, '/var/smil/playlists/3/video.mkv'));

		$this->assertFalse($method->invoke($Model, 'https://domain.tld/var/smil/playlists/3/video.mkv'));
		$this->assertFalse($method->invoke($Model, 'http://domain.tld/var/smil/playlists/3/video.mkv'));
		$this->assertFalse($method->invoke($Model, 'ftp://domain.tld/var/smil/playlists/3/video.mkv'));
		$this->assertFalse($method->invoke($Model, '/'));
	}

	// ===================== helper methods =============================================================================

	/**
	 * @return RemoteFiles
	 */
	protected function initMockAllConstructorInjections()
	{
		$this->CurlMock    = $this->createMock('Thymian\framework\Curl');
		$this->ConfigMock  = $this->createMock('Basil\helper\Configuration');
		return new RemoteFiles($this->CurlMock, $this->ConfigMock);
	}

}
