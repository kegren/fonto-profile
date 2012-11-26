<?php
/**
 * Fonto Framework
 *
 * @author Kenny Damgren <kenny.damgren@gmail.com>
 * @package Fonto
 * @link https://github.com/kenren/fonto
 */

namespace Fonto\Core\Validation\Components;

use Fonto\Core\Validation\Validator;
use Fonto\Core\Application\App;

class ValidateEmail extends Validator
{
	/**
	 * Fonto\Core\Application\App
	 *
	 * @var object
	 */
	protected $app;

	public function __construct(App $app, $value, $validateValue)
	{
		$this->app = $app;
		$this->validateAttribute($value, $validateValue);
	}

	protected function validateAttribute($value, $validateValue)
	{
		if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
			$this->setMessage($this->app->getConfig()->load('Validation', 'email'));
			$this->hasError = true;
		}
		return false;
	}
}