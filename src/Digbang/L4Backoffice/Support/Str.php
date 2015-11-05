<?php namespace Digbang\L4Backoffice\Support;

use Illuminate\Support\Str as IlluminateStr;
use Illuminate\Translation\Translator;

/**
 * Class Str
 * @package Digbang\L4Backoffice\Support
 */
class Str
{
	protected $str;
	protected $lang;

	function __construct(IlluminateStr $str, Translator $lang)
	{
		$this->str = $str;
		$this->lang = $lang;
	}

	public function titleFromSlug($slug)
	{
		return $this->str->title(str_replace(array('-', '_'), ' ', $slug));
	}

	public function parse($value)
	{
		if (is_bool($value))
		{
			return $value ? $this->lang->get('backoffice::default.yes') : $this->lang->get('backoffice::default.no');
		}

		return value($value);
	}
} 