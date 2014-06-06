<?php namespace Digbang\L4Backoffice\Support;

use Digbang\FontAwesome\Facade as FontAwesome;
use Digbang\L4Backoffice\Listings\Column;
use Illuminate\Http\Request;

class LinkMaker
{
	const SORT_BY    = 'sort_by';
	const SORT_SENSE = 'sort_sense';
	const SORT_SENSE_ASC = 'asc';
	const SORT_SENSE_DESC = 'desc';

	/**
	 * @var \Illuminate\Http\Request
	 */
	protected $request;

	function __construct(Request $request)
	{
		$this->request = $request;
	}

	public function sort(Column $column)
	{
		$parameters = $this->getParameters();
		$by         = $column->getId();
		$sense      = self::SORT_SENSE_ASC;

		if ($isSortedBy = (array_get($parameters, self::SORT_BY) == $by) && array_get($parameters, self::SORT_SENSE) == $sense)
		{
			$sense = self::SORT_SENSE_DESC;
		}

		return
			'<a href="' . $this->to($by, $sense) . '" class="sort-link">' .
				$column->getLabel() .
				FontAwesome::icon(
					$isSortedBy ? "sort-$sense" : 'sort') .
			'</a>';

	}

	protected function to($column, $sense = self::SORT_SENSE_ASC)
	{
		return $this->request->url() . '?' .
			http_build_query(array_merge(
				$this->request->except(['page']),
				[
					self::SORT_BY    => $column,
					self::SORT_SENSE => $this->parseSortSense($sense)
				]
			), null, '&');
	}

	protected function parseSortSense($sense)
	{
		return $sense == self::SORT_SENSE_DESC ?
			// its desc!
			self::SORT_SENSE_DESC :
			// its not desc, return asc.
			self::SORT_SENSE_ASC;
	}

	/**
	 * @return array
	 */
	protected function getParameters()
	{
		return $this->request->only([
			self::SORT_BY, self::SORT_SENSE
		]);
	}
}
