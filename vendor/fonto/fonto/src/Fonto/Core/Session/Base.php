<?php
/**
 * Fonto Framework
 *
 * @author Kenny Damgren <kenny.damgren@gmail.com>
 * @package Fonto
 * @link https://github.com/kenren/fonto
 */

namespace Fonto\Core\Session;

class Base
{
	/**
	 * Start session
	 */
	public function __construct()
	{
		@session_name('fonto');
		@session_start();
	}

	/**
	 * Sets a value
	 *
	 * @param string $id
	 * @param string $value
	 */
	public function set($id, $value)
	{
		$_SESSION[$id] = $value;

		return $this;
	}

	/**
	 * Returns a value from session
	 *
	 * @param  string $id
	 * @return session value
	 */
	public function get($id)
	{
		if (isset($_SESSION[$id])) {
			return $_SESSION[$id];
		}

		return false;
	}

	/**
	 * Checks if session is set returns boolean
	 *
	 * @param  string $id
	 * @return session value
	 */
	public function has($id)
	{
		if (isset($_SESSION[$id])) {
			return true;
		}

		return false;
	}


	/**
	 * Regenerates session id
	 *
	 * @return $this
	 */
	public function regenerateId()
	{
		session_regenerate_id();

		return $this;
	}

	/**
	 * Gets flash massages
	 *
	 * @param  string $id
	 * @return mixed
	 */
	public function flashMessage($id)
	{
		if (isset($_SESSION[$id])) {
			$message = $_SESSION[$id];
			unset($_SESSION[$id]);
			return $message;
		}

		return '';
	}

	/**
	 * Flushes specified session var
	 *
	 * @param  string $id
	 * @return this
	 */
	public function flush($id)
	{
		if (isset($_SESSION[$id])) {
			unset($_SESSION[$id]);
		}

		return $this;
	}

	/**
	 * Destroys all of the data associated with the current session
	 *
	 * @return void
	 */
	public function kill()
	{
		session_destroy();
	}
}