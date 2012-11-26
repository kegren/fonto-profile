<?php
/**
 * Fonto Framework
 *
 * @author Kenny Damgren <kenny.damgren@gmail.com>
 * @package Fonto
 * @link https://github.com/kenren/fonto
 */

namespace Fonto\Core\Validation;

use Fonto\Core\Application\App;

class Validator
{
	protected $rules;

	protected $errors;

	protected $hasError = false;

	protected $message;

	protected $attributes;

	protected $current;

	protected $mapRules = array(
		'max'       => 'Fonto\Core\Validation\Components\ValidateMax',
		'num'       => 'Fonto\Core\Validation\Components\ValidateNum',
		'min'       => 'Fonto\Core\Validation\Components\ValidateMin',
		'require'   => 'Fonto\Core\Validation\Components\ValidateRequire',
		'email'     => 'Fonto\Core\Validation\Components\ValidateEmail',
		'identical' => 'Fonto\Core\Validation\Components\ValidateIdentical',
	);

	/**
	 * Fonto\Core\Application\App
	 *
	 * @var object
	 */
	protected $app;

	public function __construct()
	{
		$this->errors = array();
	}

    /**
     * Sets the current application
     *
     * @param App $app
     */
	public function setApp(App $app)
	{
		$this->app = $app;
	}

	/**
	 * Returns all errors
	 *
	 * @return array
	 */
	public function getErrors()
	{
		return $this->errors;
	}

	/**
	 * Returns error based on field and type
	 *
	 * @param  string $field
	 * @param  string $type
	 * @return mixed
	 */
	public function getError($field, $type)
	{
		if (isset($this->errors[$field]) and isset($this->errors[$field][$type])) {
			return $this->errors[$field][$type];
		}

		return false;
	}

	/**
	 * Returns error for specified field
	 *
	 * @param  string $field
	 * @return mixed
	 */
	public function getErrorFor($field)
	{
		if (isset($this->errors[$field])) {
			$errors = array_keys($this->errors[$field]);

			foreach ($errors as $error) {
				$user = $this->getError($field, $error);
			}

			return $user;
		}
		return false;
	}
	/**
	 * Sets max characters
	 *
	 * @param  int $max
	 * @return Validator
	 */
	public function max($max)
	{
		$this->rules[$this->name]['max'] = (int) $max;

		return $this;
	}

	/**
	 * Sets only numbers
	 *
	 * @return Validator
	 */
	public function num()
	{
		$this->rules[$this->name]['num'] = true;

		return $this;
	}

	/**
	 * Sets min characters
	 *
	 * @param  int $min
	 * @return Validator
	 */
	public function min($min)
	{
		$this->rules[$this->name]['min'] = (int) $min;

		return $this;
	}

	/**
	 * Sets field to be required
	 *
	 * @return Validator
	 */
	public function required()
	{
		$this->rules[$this->name]['require'] = true;

		return $this;
	}

	/**
	 * Sets email validation
	 *
	 * @return Validator
	 */
	public function email()
	{
		$this->rules[$this->name]['email'] = true;

		return $this;
	}

	/**
	 * Sets identical validation
	 *
	 * @return Validator
	 */
	public function identical($with)
	{
		$this->rules[$this->name]['identical'] = $with;

		return $this;
	}


	/**
	 * Sets field name for the current validation object
	 *
	 * @param  string $name
	 * @return Validator
	 */
	public function field($name)
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * Returns true if there is no errors stored false otherwise
	 *
	 * @return boolean
	 */
	public function isValid()
	{
		return empty($this->errors);
	}

	/**
	 * Sets values for validation and sends them to
	 * the validator method
	 *
	 * @param  array  $attributes Data for validation
	 * @return void
	 */
	public function validate($attributes = array())
	{
		foreach ($attributes as $id => $value) {
			$this->attributes[$id] = $value;
		}

		foreach ($this->attributes as $id => $value) {
			if ($this->isDefined($id)) {
				$this->validator($id, $value, $this->rules[$id]);
			}
		}
	}

	/**
	 * Validates values in the proper class and sets
	 * error messages if there an error
	 *
	 * @param  string $id    Name
	 * @param  string $value Value
	 * @param  array  $rules Rules
	 * @return void
	 */
	protected function validator($id, $value, $rules)
	{
		foreach ($rules as $method => $validateValue) {
			if (array_key_exists($method, $this->mapRules)) {

				if ($method == 'identical') {
					$identicalWith = $validateValue;

					$identicalValue = $this->attributes[$identicalWith];

					$validatorClass = new $this->mapRules[$method]($this->app, $value, $identicalValue);
				} else {
					$validatorClass = new $this->mapRules[$method]($this->app, $value, $validateValue);
				}

				if ($validatorClass->hasError()) {
					$this->errors[$id][$method] = $validatorClass->getMessage();
				}
			}
		}
	}

	/**
	 * Returns true if there is an error
	 *
	 * @return boolean
	 */
	protected function hasError()
	{
		return $this->hasError;
	}

	/**
	 * Sets error message
	 *
	 * @param string $message
	 */
	protected function setMessage($message)
	{
		$this->message = $message;
	}

	/**
	 * Returns current error message
	 *
	 * @return string
	 */
	protected function getMessage()
	{
		return $this->message;
	}

	/**
	 * Checks if given id exists
	 *
	 * @param  string  $id
	 * @return boolean
	 */
	protected function isDefined($id)
	{
		return array_key_exists($id, $this->rules);
	}
}