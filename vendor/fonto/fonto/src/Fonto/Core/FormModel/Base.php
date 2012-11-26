<?php
/**
 * Fonto Framework
 *
 * @author Kenny Damgren <kenny.damgren@gmail.com>
 * @package Fonto
 * @link https://github.com/kenren/fonto
 */

namespace Fonto\Core\FormModel;

use Fonto\Core\Validation\Validator;

abstract class Base
{
	/**
	 * Rules for the form
	 *
	 * @param  Validator $validator
	 */
	public abstract function rules(Validator $validator);
}