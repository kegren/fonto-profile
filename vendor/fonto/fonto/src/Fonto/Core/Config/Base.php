<?php
/**
 * Fonto Framework
 *
 * @author Kenny Damgren <kenny.damgren@gmail.com>
 * @package Fonto
 * @link https://github.com/kenren/fonto
 */

namespace Fonto\Core\Config;

use Fonto\Core\FontoException;
use Fonto\Core\Application\App;

class Base
{
	/**
	 * Paths for config files
	 *
	 * @var string
	 */
	private $paths = array();

	/**
	 * Fonto\Core\Application\App
	 *
	 * @var object
	 */
	protected $app;


	public function __construct(array $paths)
	{
		$this->paths = $paths;
	}

	/**
	 * Sets the current application
	 *
	 * @param App $app
	 */
	public function setApp(App $app)
	{
		$this->app = $app;

		return $this;
	}

	/**
	 * Trys to read a config file from filesystem based on file and
	 * key.
	 *
	 * @param  string $file
	 * @param  string $key
	 * @return mixed
	 */
	public function load($file, $key = null)
	{
		if ($config = $this->findFile($file)) {

			if (is_callable($config[$key])) {
				return $config[$key]($this->app);
			}

			if (isset($config[$key])) {
				return $config[$key];
			}

		}

		throw new FontoException("No file with name $file was found");
	}

	/**
	 * Checks if the given config file exists
	 *
	 * @param  string $file
	 * @return file
	 */
	private function findFile($file)
	{
		foreach ($this->paths as $path) {
			$config = $path . $file . EXT;

			if (file_exists($config)) {
				return require $config;
			}
		}
	}
}