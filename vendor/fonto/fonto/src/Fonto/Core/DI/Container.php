<?php
/**
 * Fonto Framework
 *
 * @author Kenny Damgren <kenny.damgren@gmail.com>
 * @package Fonto
 * @link https://github.com/kenren/Fonto
 */

namespace Fonto\Core\DI;

use Fonto\Core\FontoException;
use Fonto\Core\Application\App;
use \ArrayAccess;
use \Closure;

class Container implements ArrayAccess
{
	/**
	 * Services in the container
	 *
	 * @var array
	 */
	protected $services = array();

	/**
	 * Fonto\Core\Application\App
	 *
	 * @var object
	 */
	protected $app;

	public function __construct(App $app)
	{
		$this->app = $app;
	}

	/**
	 * Registers a service by id
	 *
	 * @param  string $id
	 * @param  string $value
	 * @return void
	 */
	public function offsetSet($id, $value)
	{
		if (isset($this->services[$id])) {
			throw new FontoException("There is already an service with $id registered in the container");
		}

		$this->services[$id] = $value;
	}

	/**
	 * Checks if the given service is registered in the container
	 *
	 * @param  string $id
	 * @return boolean
	 */
	public function offsetExists($id)
	{
		return isset($this->services[$id]);
	}

	/**
	 * Unsets a service
	 *
	 * @param  string $id
	 * @return
	 */
	public function offsetUnset($id)
	{
		unset($this->services[$id]);
	}

	/**
	 * Returns the registered service if exists
	 *
	 * @param  string $id
	 * @return mixed
	 */
	public function offsetGet($id)
	{
		if (!isset($this->services[$id])) {
			throw new FontoException("No service is registered with name $id");
		}

		return is_callable($this->services[$id]) ? $this->services[$id]($this) : $this->services[$id];
	}

	/**
	 * Registers a callback so it is shared.
	 *
	 * @param  Closure $callback
	 * @return Closure
	 */
	public function shared(Closure $callback)
	{
		return function ($c) use ($callback) {
			static $object;

			if (null === $object) {
				$object = $callback($c);
			}

			return $object;
		};
	}
}