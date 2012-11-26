<?php
/**
 * Fonto Framework
 *
 * @author Kenny Damgren <kenny.damgren@gmail.com>
 * @package Fonto
 * @link https://github.com/kenren/fonto
 */

namespace Fonto\Core\Form;

class Form
{
	/**
	 * Returns an open form tag
	 *
	 * @param  string  $url
	 * @param  string  $method
	 * @param  array   $attributes
	 * @param  boolean $enctype
	 * @return HTML
	 */
	public function open($url, $method, $attributes = array(), $enctype = false)
	{
		$attr = '';
		$enct = '';

		if ($attributes) {
			foreach ($attributes as $id => $value) {
				$attr .= $id . '="'.$value.'"' . ' ';
			}
		}

		if ($enctype) {
			$enct = 'enctype="multipart/form-data"';
		}

		return '<form action="'.$url.'" method="'.$method.'" '.$enct.' '.$attr.'>'."\n";
	}

	/**
	 * Returns an input field
	 *
	 * @param  string $type
	 * @param  string $name
	 * @param  array  $attributes
	 * @return html
	 */
	public function input($type, $name, $attributes = array())
	{
		$attr = '';

		if ($attributes) {
			foreach ($attributes as $id => $value) {
				$attr .= $id . '="'.$value.'"' . ' ';
			}
		}

		return '<input type="'.$type.'" name="'.$name.'" id="'.$name.'" '.$attr.'>'."\n";
	}

	/**
	 * Returns a submit button
	 *
	 * @param  string $value
	 * @param  array  $attributes
	 * @return html
	 */
	public function submit($value, $attributes = array())
	{
		$attr = '';

		if ($attributes) {
			foreach ($attributes as $id => $val) {
				$attr .= $id . '="'.$val.'"' . ' ';
			}
		}

		return '<input type="submit" value="'.$value.'" '.$attr.'>'."\n";
	}

	/**
	 * Returns a label
	 *
	 * @param  string $for
	 * @param  array  $text
	 * @return html
	 */
	public function label($for, $text)
	{
		return '<label for="'.$for.'">'.$text.'</label>'."\n";
	}

	/**
	 * Returns a closing tag
	 *
	 * @return HTML
	 */
	public function close()
	{
		return '</form>'."\n";
	}
}