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

namespace Thymian\framework;

/**
 * Curl class abstracting the PHP curl functions
 *
 * Default usage:
 *
 * $request_headers = array(
 *              "User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:18.0) Gecko/20100101 Firefox/18.0",
 *              "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,* /*;q=0.8",
 * 			    "Accept-Language: de-de,de;q=0.8,en-us;q=0.5,en;q=0.3",
 *              "Accept-Encoding: deflate",
 *              "Connection: keep-alive"
 * 	);
 *
 * $Curl = new \Thymian\framework\Curl();
 * $Curl->setUrl($url)
 *      ->setPort($port)
 *      ->setVerbose()          // optional of course
 *      ->addHeaders($request_headers)
 *      ->curlExec();
 *
 * $http_status 		= $Curl->getHttpCode();
 * $response_body 		= $Curl->getResponseBody();
 * $response_headers 	= $Curl->getResponseHeaders();
 *
 * additional there is the $Curl->getCurlInfo() method
 * which returns the curl_info array, including connection time, speed, response sizes, etc...
 *
 * possible keys of a typical request (Warning, might be changed due to different requests and web server configuration!)
 * example values are from our unit tests
 *
 * array(26) {
 *      'url'                           => string (e.g. "http://smil-admin.com/garlic/server.jpg")
 *      'content_type'                  => string (e.g. "image/jpeg")
 *      'http_code'                     => int(200)
 *      'header_size'                   => int(240)
 *      'request_size'                  => int(71)
 *      'filetime'                      => int(1457204897)
 *      'ssl_verify_result'             => int(0)
 *      'redirect_count'                => int(0)
 *      'total_time'                    => double(0.12255)
 *      'namelookup_time'               => double(0.062456)
 *      'connect_time'                  => double(0.092655)
 *      'pretransfer_time'              => double(0.092697)
 *      'size_upload'                   => double(0)
 *      'size_download'                 => double(0)
 *      'speed_download'                => double(0)
 *      'speed_upload'                  => double(0)
 *      'download_content_length'       => double(40789)
 *      'upload_content_length'         => double(-1)
 *      'starttransfer_time'            => double(0.122532)
 *      'redirect_time'                 => double(0)
 *      'redirect_url'                  => string
 *      'primary_ip'                    => string (e.g. "88.198.13.194")
 *      'certinfo'                      => array(0) { }
 *      'primary_port'                  => int(80)
 *      'local_ip'                      => string (e.g. "192.168.178.26")
 *      'local_port'                    => int(36549)
 * }
 */
class Curl
{
	const REQUEST_METHOD_HEAD   = 'head';
	const REQUEST_METHOD_GET    = 'get';
	const REQUEST_METHOD_POST   = 'post';

	const DEFAULT_PORT = 80;

	/**
	 * @var string
	 */
	protected $url;

	/**
	 * @var integer
	 */
	protected $port;

	/**
	 * @var array
	 */
	protected $request_headers = array();

	/**
	 * @var boolean
	 */
	protected $verbose = false;

	/**
	 * @var boolean
	 */
	protected $verifySSL = false;

	/**
	 * @var string
	 */
	protected $user_agent = '';

	/**
	 * @var string
	 */
	protected $user_authentication = '';
	/**
	 * @var string
	 */
	protected $response_headers;

	/**
	 * @var string
	 */
	protected $response_body;

	/**
	 * @var boolean
	 */
	protected $has_error;

	/**
	 * @var integer
	 */
	protected $http_code;

	/**
	 * @var array
	 */
	protected $curl_info;

	/**
	 * @var array
	 */
	protected $curl_error;

	/**
	 * @var string
	 */
	protected $request_method;

	/**
	 * @var resource $curl_handle
	 */
	private $curl_handle;

	/**
	 * @var mixed
	 */
	protected $post_fields;

	/**
	 * @var bool
	 */
	protected $split_headers = true;

	/**
	 * Constructor
	 *
	 * @param   string	 $url
	 * @param   int		 $port
	 * @param   bool	 $verifySSL
	 * @param   array	 $headers
	 */
	public function __construct($url = '', $port = null, $verifySSL = false, $headers = array())
	{
		$this->setUrl($url)
			 ->addHeaders($headers);

		if (!is_null($port))
		{
			$this->setPort($port);
		}

		$this->verifySSL = (bool) $verifySSL;
		$this->hasError = false;

		// default is GET
		$this->request_method = self::REQUEST_METHOD_GET;
		$this->curl_handle = curl_init();
	}

	/**
	 * @param   string  $header_string
	 * @return  $this
	 */
	public function addHeader($header_string)
	{
		$this->request_headers[] = $header_string;
		return $this;
	}

	/**
	 * @param array $ar_headers
	 * @return $this
	 */
	public function addHeaders(array $ar_headers)
	{
		$this->request_headers = array_merge($this->request_headers, $ar_headers);
		return $this;
	}

	/**
	 * @return array
	 */
	public function getHeaders()
	{
		return $this->request_headers;
	}

	/**
	 * @param   boolean $verbose
	 * @return  $this
	 */
	public function setVerbose($verbose = false)
	{
		$this->verbose = (bool) $verbose;
		return $this;
	}

	/**
	 * @param   integer $port
	 * @return  $this
	 */
	public function setPort($port = self::DEFAULT_PORT)
	{
		$this->port = (int) $port;
		return $this;
	}

	/**
	 * @param   string $url
	 * @return  $this
	 */
	public function setUrl($url = '')
	{
		$this->url = $url;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getUserAgent()
	{
		return $this->user_agent;
	}

	/**
	 * @param string $user_agent
	 * @return $this
	 */
	public function setUserAgent($user_agent)
	{
		$this->user_agent = $user_agent;
		return $this;
	}

	/**
	 * @return $this
	 */
	public function clearHeaders()
	{
		$this->request_headers = array();
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function hasSplitHeaders()
	{
		return $this->split_headers;
	}

	/**
	 * @param boolean $split_headers
	 * @return $this
	 */
	public function setSplitHeaders($split_headers)
	{
		$this->split_headers = (bool) $split_headers;
		return $this;
	}

	/**
	 * @return $this
	 */
	public function removeUserAuthentication()
	{
		$this->user_authentication = '';
		return $this;
	}

	/**
	 * @return bool
	 */
	public function hasUserAuthentication()
	{
		return (!empty($this->user_authentication));
	}

	/**
	 * @param   string  $user
	 * @param   string  $password
	 * @return $this
	 */
	public function setUserAuthentication($user, $password)
	{
		$this->user_authentication = $user . ':' . $password;
		return $this;
	}

	/**
	 * let curl do his work
	 *
	 * @param   bool    $split_headers    determine if we return the split the response to headers and body (true/default) or combine the result (false). Needed for feeds
	 * @return $this
	 * @throws \Exception
	 */
	public function curlExec($split_headers = true)
	{
		if (empty($this->url))
		{
			throw new \Exception('No URL is set for curl');
		}

		$this->setOptions();

		$response = curl_exec($this->curl_handle);

		$this->curl_error = array(
			'err_no' => curl_errno($this->curl_handle),
			'err_ms' => curl_error($this->curl_handle)
		);

		$this->curl_info = curl_getinfo($this->curl_handle);

		$this->setSplitHeaders($split_headers);
		$this->parseResponse($response);

		return $this;
	}

	/**
	 * @return array
	 */
	public function getCurlInfo()
	{
		return $this->curl_info;
	}

	/**
	 * @param   string  $curl_info_key
	 * @return mixed|null
	 */
	public function getCurlInfoByKey($curl_info_key)
	{
		if (array_key_exists($curl_info_key, $this->curl_info))
		{
			return $this->curl_info[$curl_info_key];
		}

		return null;
	}

	/**
	 * @return string
	 */
	public function getErrorMessage()
	{
		return $this->curl_error['err_ms'];
	}

	/**
	 * @return integer
	 */
	public function getErrorNumber()
	{
		return $this->curl_error['err_no'];
	}

	/**
	 * @return string
	 */
	public function getResponseHeaders()
	{
		return $this->response_headers;
	}

	/**
	 * @return string
	 */
	public function getResponseBody()
	{
		return $this->response_body;
	}

	/**
	 * @return integer
	 */
	public function getHttpCode()
	{
		return $this->http_code;
	}

	/**
	 * sets the request method to POST
	 *
	 * @return $this
	 */
	public function setRequestMethodPost()
	{
		$this->request_method = self::REQUEST_METHOD_POST;
		return $this;
	}

	/**
	 * sets the request method to GET
	 *
	 * @return $this
	 */
	public function setRequestMethodGet()
	{
		$this->request_method = self::REQUEST_METHOD_GET;
		return $this;
	}

	/**
	 * @return $this
	 */
	public function setRequestMethodHead()
	{
		$this->request_method = self::REQUEST_METHOD_HEAD;
		return $this;
	}

	/**
	 * Keep in mind!
	 * If you use an array as post fields, curl does a multipart format post
	 * If you want a regular unipart post, use a string like name=John&surname=Doe&age=36
	 * @see http_build_query()
	 *
	 * @param array|string  $values
	 * @return Curl
	 * @throws \Exception
	 */
	public function setPostFields($values)
	{
		if (is_array($values) || is_string($values))
		{
			curl_setopt($this->curl_handle, CURLOPT_POSTFIELDS, $values);
			return $this->setRequestMethodPost();
		}
		throw new \Exception('Post fields values must be of type string or array.');
	}

	/**
	 * sets the curl options via curl_setopt();
	 *
	 * @return $this
	 */
	private function setOptions()
	{
		$this->setBasicOptions();

		$setHeaders = (count($this->request_headers) > 0);

		if ($setHeaders)
		{
			$this->setHeaderOptions();
		}
		else
		{
			// disable any headers
			curl_setopt($this->curl_handle, CURLOPT_HEADER, 0);
		}

		curl_setopt($this->curl_handle, CURLOPT_SSL_VERIFYPEER, ($this->verifySSL == false) ? 0 : 1);
		curl_setopt($this->curl_handle, CURLOPT_RETURNTRANSFER, 1);

		switch($this->request_method)
		{
			case self::REQUEST_METHOD_GET:
				curl_setopt($this->curl_handle, CURLOPT_NOBODY, 0);
				curl_setopt($this->curl_handle, CURLOPT_HTTPGET, 1);
				break;

			case self::REQUEST_METHOD_POST:
				curl_setopt($this->curl_handle, CURLOPT_NOBODY, 0);
				curl_setopt($this->curl_handle, CURLOPT_POST, 1);
				break;

			case self::REQUEST_METHOD_HEAD:
				curl_setopt($this->curl_handle, CURLOPT_HTTPGET, 1);
				curl_setopt($this->curl_handle, CURLOPT_NOBODY, 1);
				curl_setopt($this->curl_handle, CURLOPT_FILETIME, 1);
				break;
		}

		return $this;
	}

	/**
	 * set basic options for all calls
	 *
	 * @return $this
	 */
	private function setBasicOptions()
	{
		curl_setopt($this->curl_handle, CURLOPT_URL, $this->url);
		curl_setopt($this->curl_handle, CURLOPT_PORT, $this->port);
		curl_setopt($this->curl_handle, CURLOPT_VERBOSE, ($this->verbose == false) ? 0 : 1);
		curl_setopt($this->curl_handle, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
		curl_setopt($this->curl_handle, CURLOPT_DNS_USE_GLOBAL_CACHE, false );
		curl_setopt($this->curl_handle, CURLOPT_DNS_CACHE_TIMEOUT, 1 );

		if (!empty($this->user_agent))
		{
			curl_setopt($this->curl_handle, CURLOPT_USERAGENT, $this->user_agent);
		}

		if (!empty($this->user_authentication))
		{
			curl_setopt($this->curl_handle, CURLOPT_USERPWD, $this->user_authentication);
		}

		return $this;
	}

	/**
	 * @return $this
	 */
	private function setHeaderOptions()
	{
		curl_setopt($this->curl_handle, CURLOPT_HEADER, 1);
		curl_setopt($this->curl_handle, CURLOPT_HTTPHEADER, $this->request_headers);

		if ($this->verbose === true)
		{
			curl_setopt($this->curl_handle, CURLINFO_HEADER_OUT, 1);
		}

		return $this;
	}

	/**
	 * @param string $response
	 *
	 * @return $this
	 */
	private function parseResponse($response)
	{
		if ($this->split_headers == true)
		{
			$header_size = $this->curl_info['header_size'];
			$this->response_headers = substr($response, 0, $header_size);
			$this->response_body = substr($response, $header_size);
		}
		else
		{
			$this->response_headers = '';
			$this->response_body    = $response;
		}

		$this->http_code = (int) $this->curl_info['http_code'];

		return $this;
	}
}