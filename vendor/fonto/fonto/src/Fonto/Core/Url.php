<?php
/**
 * Fonto Framework
 *
 * @author Kenny Damgren <kenny.damgren@gmail.com>
 * @package Fonto
 * @link https://github.com/kenren/fonto
 */

namespace Fonto\Core;

class Url
{

	/**
	 * Create base url for application.
	 *
	 * @return string base url
	 */
	public function baseUrl()
	{
		$url = '';
		if (isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] == 'on') {
			$url = 'https';
		} else {
			$url = 'http';
		}
		$url .= '://' . $_SERVER['HTTP_HOST'];
		$url .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);

		return (string) $url;
	}

}