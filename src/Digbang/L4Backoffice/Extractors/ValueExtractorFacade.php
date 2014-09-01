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
	 * @param        $element
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
		if ($element instanceof Model)
		{
			return $this->eloquentExtractor();
		}

		return $this->arrayExtractor();
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
}