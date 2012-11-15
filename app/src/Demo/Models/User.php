<?php

namespace Demo\Models;

class User extends \ActiveRecord\Model
{
	/**
	 * Defines relationships
	 *
	 * @var array
	 */
	public static $has_many = array(
     	array('roles', 'through' => 'userroles')
	);

	/**
	 * Updates a record
	 *
	 * @param  string  $userId
	 * @param  string  $username
	 * @param  boolean $password
	 * @param  string  $email
	 * @param  string  $name
	 * @return object
	 */
	public function updateRecord($userId, $username, $password = false, $email, $name)
	{
		if (false === $password) {
			$user = User::find($userId);
			$user->username = $username;
			$user->email = $email;
			$user->name = $name;
			$rtn = $user->save();

			return $rtn;
		} else {
			$user = User::find($userId);
			$user->username = $username;
			$user->password = $password;
			$user->email = $email;
			$user->name = $name;
			$rtn = $user->save();

			return $rtn;
		}
	}

	/**
	 * Creates a record
	 *
	 * @param  array $credentials User submitted data
	 * @return object
	 */
	public function createRecord($credentials)
	{
		$user = User::create($credentials);

		return $user;
	}
}