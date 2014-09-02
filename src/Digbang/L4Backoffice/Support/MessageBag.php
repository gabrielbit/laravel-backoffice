<?php namespace Digbang\L4Backoffice\Support;
/**
 * Class MessageBag
 * @package Digbang\L4Backoffice\Support
 */
class MessageBag extends \Illuminate\Support\MessageBag
{
	public function hasAny($keys)
	{
		foreach ((array) $keys as $key)
		{
			if ($this->has($key))
			{
				return true;
			}
		}

		return false;
	}

	public function get($key, $format = null)
	{
		if (is_array($key))
		{
			return $this->getAll($key, $format);
		}

		return parent::get($key, $format);
	}

	public function getAll(array $keys, $format = null)
	{
		$output = [];

		foreach ($keys as $key)
		{
			$output = array_merge($output, parent::get($key, $format));
		}

		return $output;
	}
} 