<?php
/**
 * Fonto Framework
 *
 * @author Kenny Damgren <kenny.damgren@gmail.com>
 * @package Fonto
 * @link https://github.com/kenren/fonto
 */

namespace Fonto\Core\Security;

class Hash
{
	private $algorithm;

	private $salt;

	public function __construct($algorithm = null)
	{
		if (null === $algorithm) {
			$this->algorithm = 'sha256';
		} else {
			$this->algorithm = $algorithm;
		}
		$this->salt = hash('sha256', microtime());
	}

	public function makeHash($hashable)
	{
		$hash = hash($this->algorithm, $hashable . $this->salt);

		return array('hash' => $hash, 'salt' => $this->salt);
	}

	public function checkHash($plain, $salt, $hash)
	{
		$plainHashed = hash($this->algorithm, $plain . $this->salt);

		if ($plainHashed == $hash) {
			return true;
		}

		return false;
	}
}