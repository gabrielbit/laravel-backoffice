<?php namespace Digbang\L4Backoffice\Filters;

use Illuminate\Support\Contracts\RenderableInterface;

interface FilterInterface extends RenderableInterface
{
	/**
	 * The filter name, as will be sent in the request.
	 * @return string
	 */
	public function name();

	/**
	 * A filter label, a text that indicates what it is.
	 * @return string
	 */
	public function label();

	/**
	 * The filter value as received from the user.
	 * @return string It may well be an integer, but everything is stringy from HTTP requests...
	 */
	public function value();

	/**
	 * The filter HTML options.
	 * @return \Illuminate\Support\Collection
	 */
	public function options();

	/**
	 * The filter default value, used before the user interacts.
	 * @param $value
	 */
	public function defaultsTo($value);
}