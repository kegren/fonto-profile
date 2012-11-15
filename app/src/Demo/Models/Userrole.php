<?php

namespace Demo\Models;

class Userrole extends \ActiveRecord\Model
{
	/**
	 * Defines relationships
	 *
	 * @var array
	 */
	static $belongs_to = array(
		array('user'),
		array('role')
	);
}