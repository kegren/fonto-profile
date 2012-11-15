<?php

namespace Demo\Models;

class Role extends \ActiveRecord\Model
{
	/**
	 * Defines relationships
	 *
	 * @var array
	 */
	public static $has_many = array(
     	array('userroles')
	);
}