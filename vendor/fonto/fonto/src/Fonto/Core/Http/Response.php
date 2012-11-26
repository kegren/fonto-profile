<?php
/**
 * Fonto Framework
 *
 * @author Kenny Damgren <kenny.damgren@gmail.com>
 * @package Fonto
 * @link https://github.com/kenren/fonto
 */

namespace Fonto\Core\Http;

class Response
{
	public function __construct()
	{}

	/**
	 * Redirects to given uri
	 *
	 * @param  string $to
	 */
	public function redirect($to)
	{
		header("Location: $to");
		exit;
	}
}