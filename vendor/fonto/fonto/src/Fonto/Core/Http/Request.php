<?php
/**
 * Fonto Framework
 *
 * @author Kenny Damgren <kenny.damgren@gmail.com>
 * @package Fonto
 * @link https://github.com/kenren/fonto
 */

namespace Fonto\Core\Http;

class Request
{
	/**
	 * @var string Request method
	 */
	private $method = 'GET';

	/**
	 * @var string Requested Uri
	 */
	private $requestUri;

	/**
	 * @var string Path for the current script
	 */
	private $scriptName;

	public function __construct()
	{
		if (isset($_SERVER['REQUEST_METHOD'])) {
			$this->method = $_SERVER['REQUEST_METHOD'];
		}
		if (isset($_SERVER['REQUEST_URI'])) {
			$this->requestUri = $_SERVER['REQUEST_URI'];
		}
		if (isset($_SERVER['SCRIPT_NAME'])) {
			$this->scriptName = $_SERVER['SCRIPT_NAME'];
		}
	}

	/**
	 * Returns current method
	 *
	 * @return string
	 */
	public function getMethod()
	{
		return $this->method;
	}

	/**
	 * Returns true if the current method is post false otherwise
	 *
	 * @return boolean
	 */
	public function isPost()
	{
		return $this->method === 'POST';
	}

	/**
	 * Gets this instance if the request is post
	 *
	 * @return mixed
	 */
	public function post()
	{
		if ($this->isPost()) {
			return $this;
		}

		throw new FontoException("Http method need to be POST");
	}

	/**
	 * Returns all post data
	 *
	 * @return $_POST
	 */
	public function getAll()
	{
		return $_POST;
	}

	/**
	 * Returns specified post
	 *
	 * @param  string $get
	 * @return $_POST
	 */
	public function get($get)
	{
		return $_POST[$get];
	}

	/**
	 * Returns requested uri
	 *
	 * @return array uri
	 */
	public function getRequestUri()
	{
		$uri = $this->parseRequestUri();
		return $uri;
	}

	/**
	 * Returns current script path
	 *
	 * @return string
	 */
	public function getScriptName()
	{
		return $this->scriptName;
	}

	/**
	 * Remove dirname from uri if needed
	 *
	 * @return array uri
	 */
	private function parseRequestUri()
	{
		$uri = $this->requestUri;

		if (strpos($uri, dirname($this->scriptName)) === 0) {
			$uri = substr($uri, strlen(dirname($this->scriptName)));
		}

		return $uri;
	}
}