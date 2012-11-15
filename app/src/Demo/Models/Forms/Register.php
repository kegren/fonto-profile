<?php

namespace Demo\Models\Forms;

use Fonto\Core\FormModel\Base;
use Fonto\Core\Validation\Validator;

class Register extends Base
{
	/**
	 * Sets rules for 'Registration form'
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

			$validator->field('password')
					  ->max(32)
					  ->min(5)
					  ->required();

			$validator->field('password_repeat')
					  ->max(32)
					  ->min(5)
					  ->identical('password')
					  ->required();

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