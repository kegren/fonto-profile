<?php

namespace Demo\Models\Forms;

use Fonto\Core\FormModel\Base;
use Fonto\Core\Validation\Validator;

class Profile extends Base
{
	/**
	 * Sets rules for 'Profile form'
	 *
	 * @param  Validator $validator
	 * @return Closure
	 */
	public function rules(Validator $validator)
	{
		$rules = function() use ($validator) {
			$validator->field('username')
					  ->max(32)
                      ->min(2)
					  ->required();

			$validator->field('password');

			$validator->field('password_repeat')
					  ->identical('password');

			$validator->field('name')
					  ->max(32)
					  ->min(2)
					  ->required();

			$validator->field('email')
					  ->email()
					  ->required();
		};

		return $rules();
	}
}