<?php namespace Digbang\L4Backoffice\Filters;

use Digbang\L4Backoffice\Controls\ControlInterface;

interface FilterInterface extends ControlInterface
{
	/**
	 * The filter name, as will be sent in the request.
	 * @return string
	 */
	public function name();

	/**
	 * The filter value as received from the user.
	 * @return string It may well be an integer, but everything is stringy from HTTP requests...
	 */
	public function value();

	/**
	 * The filter default value, used before the user interacts.
	 * @param $value
	 */
	public function defaultsTo($value);
}