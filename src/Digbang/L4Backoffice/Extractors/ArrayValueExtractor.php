<?php namespace Digbang\L4Backoffice\Extractors;
use Illuminate\Support\Contracts\ArrayableInterface;

/**
 * Class ArrayValueExtractor
 * @package Digbang\L4Backoffice\Extractors
 */
class ArrayValueExtractor implements ValueExtractor
{
	/**
	 * @param        $element
	 * @param string $key
	 *
	 * @throws \UnexpectedValueException
	 * @return string the extracted value
	 */
	public function extract($element, $key)
	{
		if (! $this->validate($element))
		{
			throw new \UnexpectedValueException("Cannot extract from this type of object.");
		}

		if (! $this->exists($element, $key))
		{
			throw new \UnexpectedValueException("Key $key does not exist in given array.");
		}

		return $this->getValue($element, $key);
	}

	protected function validate($element)
	{
		return is_array($element) || $element instanceof \ArrayAccess || $element instanceof ArrayableInterface;
	}

	/**
	 * @param $element
	 * @param $key
	 *
	 * @return bool
	 */
	protected function exists($element, $key)
	{
		if ($element instanceof ArrayableInterface)
		{
			$element = $element->toArray();
		}

		return isset($element[$key]);
	}

	/**
	 * @param $element
	 * @param $key
	 *
	 * @return mixed
	 */
	protected function getValue($element, $key)
	{
		if ($element instanceof ArrayableInterface)
		{
			$element = $element->toArray();
		}

		return $element[$key];
	}
}