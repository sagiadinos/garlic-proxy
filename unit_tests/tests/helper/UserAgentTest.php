<?php
/*************************************************************************************
basil-proxy: A proxy solution for Digital Signage SMIL Player
Copyright (C) 2018 Nikolaos Sagiadinos <ns@smil-control.com>
This file is part of the basil-proxy source code
This program is free software: you can redistribute it and/or  modify
it under the terms of the GNU Affero General Public License, version 3,
as published by the Free Software Foundation.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.
You should have received a copy of the GNU Affero General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *************************************************************************************/

use Basil\helper\UserAgent;
use PHPUnit\Framework\TestCase;

class UserAgentTest extends TestCase
{
	/**
	 * @group units
	 */
	public function testSetInfoFromAgentString()
	{
		$Controller = new UserAgent();
		$http_agent = 'ADAPI/1.0 (UUID:b8375cab-c52f-40ce-b51f-001060b32d06; NAME:testplayername) SK8855-ADAPI/2.0.5 (MODEL:XDS-101)';
		$ar_info = $Controller->setInfoFromAgentString($http_agent)->getInfo();
		$ar_expected = array('uuid'             => 'b8375cab-c52f-40ce-b51f-001060b32d06',
							 'firmware_version' => 'SK8855-ADAPI/2.0.5',
							 'name'             => 'testplayername',
							 'model'            => 'XDS-101'
		);
		$this->assertEquals($ar_expected, $ar_info);
		$this->assertTrue($Controller->isDsPlayer());
		$this->assertEquals($http_agent, $Controller->getAgentString());
		$this->assertEquals('b8375cab-c52f-40ce-b51f-001060b32d06', $Controller->getUuid());
		$this->assertEquals('testplayername', $Controller->getName());
	}

	/**
	 * @group units
	 */
	public function testSetInfoFromAgentStringWithSpaces()
	{
		$Controller = new UserAgent();
		// check with spaces in playername
		$http_agent = 'ADAPI/1.0 (UUID:a8294bat-c28f-50af-f94o-800869af5854; NAME:Player with spaces in name) SK8855-ADAPI/2.0.5 (MODEL:XMP-330)';
		$ar_info = $Controller->setInfoFromAgentString($http_agent)->getInfo();
		$ar_expected = array('uuid' => 'a8294bat-c28f-50af-f94o-800869af5854', 'firmware_version' => 'SK8855-ADAPI/2.0.5', 'name' => 'Player with spaces in name', 'model' => 'XMP-330');
		$this->assertEquals($ar_expected, $ar_info);
		$this->assertTrue($Controller->isDsPlayer());
	}

	/**
	 * @group units
	 */
	public function testSetInfoFromAgentStringWithDeprecatedIadea()
	{
		$Controller = new UserAgent();
		// deprecated IAdea Android Player
		$http_agent = 'Mozilla/5.0 (Linux; U; Android 4.0.4; en-us; Build/ICS.MBX.20121225) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Safari/534.30 
				ADAPI/2.0 (UUID:80db43f3-c323-41b5-914a-d0aeece2df95) AML8726M3-ADAPI/20121225.020028 (MODEL:XMP-2200)';
		$ar_info = $Controller->setInfoFromAgentString($http_agent)->getInfo();
		$ar_expected = array('uuid' => '80db43f3-c323-41b5-914a-d0aeece2df95', 'firmware_version' => 'ADAPI/20121225.020028', 'name' => 'AML8726M3', 'model' => 'XMP-2200');
		$this->assertEquals($ar_expected, $ar_info);
		$this->assertTrue($Controller->isDsPlayer());
	}

	/**
	 * @group units
	 */
	public function testSetInfoFromAgentStringWith4DMAgic()
	{
		$Controller = new UserAgent();
		// Netopsie player
		$http_agent = 'ADAPI/1.0 (UUID:80db43f3-c323-41b5-abcd-542aa2fff06c; NAME:TEST PLAYER) WIN74D-ADAPI/2.0.0 (MODEL:GDATA-1100)';
		$ar_info = $Controller->setInfoFromAgentString($http_agent)->getInfo();
		$ar_expected = array('uuid' => '80db43f3-c323-41b5-abcd-542aa2fff06c', 'firmware_version' => 'WIN74D-ADAPI/2.0.0', 'name' => 'TEST PLAYER', 'model' => 'GDATA-1100');
		$this->assertEquals($ar_expected, $ar_info);
		$this->assertTrue($Controller->isDsPlayer());
	}

	/**
	 * @group units
	 */
	public function testSetInfoFromAgentStringWithIadeaAndroid()
	{
		$Controller = new UserAgent();
		// New Iadeas
		$http_agent = 'ADAPI/2.0 (UUID:9e7df0ed-2a5c-4a19-bec7-2cc548004d30) RK3188-ADAPI/1.2.59.161 (MODEL:XMP-6250)';
		$ar_info = $Controller->setInfoFromAgentString($http_agent)->getInfo();
		$ar_expected = array('uuid' => '9e7df0ed-2a5c-4a19-bec7-2cc548004d30', 'firmware_version' => 'ADAPI/1.2.59.161', 'name' => 'RK3188', 'model' => 'XMP-6250');
		$this->assertEquals($ar_expected, $ar_info);
		$this->assertTrue($Controller->isDsPlayer());
	}

	/**
	 * @group units
	 */
	public function testSetInfoFromAgentStringWithPartialMatching()
	{
		$Controller = new UserAgent();
		$http_agent = 'ADAPI/2.0 (UUID:0d8df0bd-3a5c-4a19-bec7-ecf00e3012e6;)';
		$ar_info = $Controller->setInfoFromAgentString($http_agent)->getInfo();
		$ar_expected = array('uuid' => '', 'firmware_version' => '', 'name' => '', 'model' => '');
		$this->assertEquals($ar_expected, $ar_info);
		$this->assertFalse($Controller->isDsPlayer());
	}

	/**
	 * @group units
	 */
	public function testSetInfoFromAgentStringFailed()
	{
		$Controller = new UserAgent();
		$http_agent = 'Mozilla/5.0 (Linux; U; Android 4.0.4; en-us; Build/ICS.MBX.20121225) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Safari/534.30';
		$ar_info = $Controller->setInfoFromAgentString($http_agent)->getInfo();
		$ar_expected = array('uuid' => '', 'firmware_version' => '', 'name' => '', 'model' => '');
		$this->assertEquals($ar_expected, $ar_info);
		$this->assertFalse($Controller->isDsPlayer());
	}

	/**
	 * @group units
	 */
	public function testSetInfoFromAgentStringWithQBic()
	{
		$Controller = new UserAgent();
		// QBic player
		$http_agent = 'SmartAPI/1.0 (UUID:cc009f47-5a8d-42b4-af5a-1865710c05ba; NAME:05B200T100223; VERSION:v1.0.16; MODEL:TD-1050)';
		$ar_info = $Controller->setInfoFromAgentString($http_agent)->getInfo();
		$ar_expected = array('uuid' => 'cc009f47-5a8d-42b4-af5a-1865710c05ba', 'firmware_version' => 'v1.0.16', 'name' => '05B200T100223', 'model' => 'TD-1050');
		$this->assertEquals($ar_expected, $ar_info);
		$this->assertTrue($Controller->isDsPlayer());
	}

	/**
	 * @group units
	 */
	public function testSetInfoFromAgentStringWithIDS()
	{
		$Controller = new UserAgent();
		// IDS -App
		$http_agent = 'ADAPI/1.0 (UUID:898a48587eb9f96f; NAME:Android-App-898a48587eb9f96f) Android/1.0.180vv (MODEL:IDS-App)';
		$ar_info = $Controller->setInfoFromAgentString($http_agent)->getInfo();
		$ar_expected = array('uuid' => '898a48587eb9f96f', 'firmware_version' => 'Android/1.0.180vv', 'name' => 'Android-App-898a48587eb9f96f', 'model' => 'IDS-App');
		$this->assertEquals($ar_expected, $ar_info);
		$this->assertTrue($Controller->isDsPlayer());
		return;
	}

}
