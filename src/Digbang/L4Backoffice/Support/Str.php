<?php namespace Digbang\L4Backoffice\Support;

use Illuminate\Support\Str as IlluminateStr;

/**
 * Class Str
 * @package Digbang\L4Backoffice\Support
 */
class Str
{
	protected $str;

	function __construct(IlluminateStr $str)
	{
		$this->str = $str;
	}

	public function titleFromSlug($slug)
	{
		return $this->str->title(str_replace(array('-', '_'), ' ', $slug));
	}
} 