<?php namespace Digbang\L4Backoffice\Extractors;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EloquentValueExtractor
 * @package Digbang\L4Backoffice\Extractors
 */
class EloquentValueExtractor implements ValueExtractor
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
		if (! $element instanceof Model)
		{
			throw new \UnexpectedValueException("Given object must be an Eloquent\\Model.");
		}

		$output = $element->{$key};

		if ($output instanceof Carbon)
		{
			return $output->toDateTimeString();
		}

		return $output;
	}
} 