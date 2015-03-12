<?php namespace Digbang\L4Backoffice\Generator\Model;

use Illuminate\Support\Str;

class StrDecorator
{
	/**
	 * @type string
	 */
	private $string;

	/**
	 * @param string $string
	 */
	public function __construct($string = '')
	{
		$this->string = $string;
	}

	/**
	 * @param string $string
	 */
	public function setString($string)
	{
		$this->string = $string;
	}

	/**
	 * @return static
	 */
	public function studly()
	{
		return new static(Str::studly($this->string));
	}

	/**
	 * @return static
	 */
	public function title()
	{
		return new static(Str::title(str_replace('_', ' ', Str::snake($this->string))));
	}

	/**
	 * @return static
	 */
	public function plural()
	{
		return new static(Str::plural($this->string));
	}

	/**
	 * @return static
	 */
	public function snake()
	{
		return new static(Str::snake(str_replace(' ', '_', $this->string)));
	}

	/**
	 * @return static
	 */
	public function camel()
	{
		return new static(Str::camel($this->string));
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->string;
	}
}
