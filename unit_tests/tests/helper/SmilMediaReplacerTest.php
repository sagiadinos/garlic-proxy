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


use Basil\helper\SmilMediaReplacer;
use PHPUnit\Framework\TestCase;

class SmilMediaReplacerTest extends TestCase
{
	protected $CurlMock;

	/**
	 *  unset all Mocks
	 */
	protected function tearDown()
	{
		unset($this->CurlMock);
	}

	/**
	 * @group units
	 */
	public function testReplaceWithRelative()
	{
		$Helper = $this->initMockAllConstructorInjections();
		$method = \PHPUnitUtils::getProtectedMethod($Helper, 'setMatches');
		$data   = array(0  => 'var/smil/playlists/3/video.mkv');
		$method->invoke($Helper, $data);
		$expected = 'var/media/d6baf4644d11a65aec31791a926f5500.mkv';
		$this->assertContains($expected, $Helper->getSmil());
	}


	/**
	 * @group units
	 */
	public function testFindMatches()
	{
		$Helper = $this->initMockAllConstructorInjections();
		$returned = $Helper->findMatches();
		$expected = array(
							0  => 'var/smil/playlists/3/video.mkv',
							1  => 'var/smil/playlists/3/image1.jpg',
							2  => 'var/smil/playlists/3/image2.png',
							3  => 'https://static.basil.dev/var/smil/playlists/3/widget.wgt',
							4  => 'var/smil/playlists/3/audio.mp3',
							5  => 'https://foreign-server.tld/content.html',
							11 => 'adapi:blankScreen'
		);
		$this->assertEquals($expected, $returned);
	}


	// ===================== helper methods =============================================================================

	/**
	 * @return SmilMediaReplacer
	 */
	protected function initMockAllConstructorInjections()
	{
		$this->CurlMock  = $this->createMock('Thymian\framework\Curl');

		$smil = file_get_contents(_ResourcesPath.'/indexes/has_all_media.smil');
		return new SmilMediaReplacer($smil, $this->CurlMock);

	}


}
