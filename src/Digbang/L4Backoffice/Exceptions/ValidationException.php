<?php namespace Digbang\L4Backoffice\Exceptions;

/**
 * Class ValidationException
 * @package Digbang\L4Backoffice\Exceptions
 */
class ValidationException extends \RuntimeException
{
	protected $errors;

	public function __construct($errors, $message = "", $code = 0, \Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);

		$this->errors = $errors;
	}

	public function getErrors()
	{
		return $this->errors;
	}
}