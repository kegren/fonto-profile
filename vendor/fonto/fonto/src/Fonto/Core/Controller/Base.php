<?php
/**
 * Fonto Framework
 *
 * @author Kenny Damgren <kenny.damgren@gmail.com>
 * @package Fonto
 * @link https://github.com/kenren/fonto
 */

namespace Fonto\Core\Controller;

use Fonto\Core\View;
use Fonto\Core\Application\App;

class Base
{
	/**
	 * Fonto\Core\Application\App
	 *
	 * @var object
	 */
	protected $app;

	public function __construct()
	{}

	/**
	 * Sets the current application
	 *
	 * @param App $app
	 */
	public function setApp(App $app)
	{
		$this->app = $app;
	}

	/**
	 * Magic method
	 *
	 * @param $class
	 * @param $args
	 */
	public function __call($class, $args)
	{
		return $this->app->container[$class];
	}
}