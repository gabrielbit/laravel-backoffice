<?php namespace Digbang\L4Backoffice\Extractors;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ValueExtractorFacade
 * @package Digbang\L4Backoffice\Extractors
 */
class ValueExtractorFacade implements ValueExtractor
{
	/** @var array Flyweight pattern */
	protected $extractors = [];

	/**
	 * @param mixed  $element
	 * @param string $key
	 *
	 * @return string the extracted value
	 */
	public function extract($element, $key)
	{
		$extractor = $this->getExtractorFor($element);

		return $extractor->extract($element, $key);
	}

	/**
	 * @param $element
	 *
	 * @return ValueExtractor
	 */
	protected function getExtractorFor($element)
	{
		switch (true)
		{
			case $element instanceof Model:
				return $this->eloquentExtractor();
			case is_array($element):
				return $this->arrayExtractor();
			case is_object($element):
				return $this->objectExtractor();
		}

		throw new \UnexpectedValueException("Unable to extract values from given element.");
	}

	/**
	 * @return EloquentValueExtractor
	 */
	protected function eloquentExtractor()
	{
		if (!array_key_exists('eloquent', $this->extractors))
		{
			$this->extractors['eloquent'] = new EloquentValueExtractor();
		}

		return $this->extractors['eloquent'];
	}

	/**
	 * @return ArrayValueExtractor
	 */
	protected function arrayExtractor()
	{
		if (!array_key_exists('array', $this->extractors))
		{
			$this->extractors['array'] = new ArrayValueExtractor();
		}

		return $this->extractors['array'];
	}

	/**
	 * @return ObjectValueExtractor
	 */
	protected function objectExtractor()
	{
		if (!array_key_exists('object', $this->extractors))
		{
			$this->extractors['object'] = new ObjectValueExtractor();
		}

		return $this->extractors['object'];
	}
}