<?php namespace Digbang\L4Backoffice\Extractors;

interface ValueExtractor
{
	/**
	 * @param $element
	 * @param string $key
	 *
	 * @return string the extracted value
	 */
	public function extract($element, $key);
} 