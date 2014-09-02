<?php namespace Digbang\L4Backoffice\Inputs;

use Digbang\L4Backoffice\Controls\ControlInterface;

interface InputInterface extends ControlInterface
{
	/**
	 * The input name, as will be sent in the request.
	 * @return string
	 */
	public function name();

	/**
	 * The input value as received from the user.
	 * @return string It may well be an integer, but everything is stringy from HTTP requests...
	 */
	public function value();

	/**
	 * The input default value, used before the user interacts.
	 * @param $value
	 */
	public function defaultsTo($value);

	/**
	 * Check if the input matches the given name
	 * @param $name
	 * @return boolean
	 */
	public function hasName($name);

	/**
	 * Sets the value of the input
	 * @param $value
	 * @return void
	 */
	public function setValue($value);
}