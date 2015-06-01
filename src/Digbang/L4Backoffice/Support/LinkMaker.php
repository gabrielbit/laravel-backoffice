<?php namespace Digbang\L4Backoffice\Support;

use Digbang\FontAwesome\FontAwesome;
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
	protected $fontAwesome;

	function __construct(Request $request, FontAwesome $fontAwesome)
	{
		$this->request = $request;
		$this->fontAwesome = $fontAwesome;
	}

	public function sort(Column $column, $defaultOrder = null)
	{
		$parameters = $this->getParameters();
		$by         = $column->getId();

		if (!isset($parameters[self::SORT_BY]) && $defaultOrder !== null)
		{
			$parameters[self::SORT_BY] = $defaultOrder['by'];
			$parameters[self::SORT_SENSE] = strtolower($defaultOrder['sense']);
		}

		$sortedBy = array_get($parameters, self::SORT_BY);
		$senseBy = array_get($parameters, self::SORT_SENSE);

		$isSortedBy = $sortedBy == $by;

		$sense = self::SORT_SENSE_ASC;
		if ($isSortedBy && $senseBy == self::SORT_SENSE_ASC)
		{
			$sense = self::SORT_SENSE_DESC;
		}

		return
			'<a href="' . $this->to($by, $sense) . '" class="sort-link">' .
				($isSortedBy ? sprintf('<strong>%1$s</strong>', $column->getLabel()) : $column->getLabel()) .
				$this->fontAwesome->icon($isSortedBy ? "sort-$senseBy" : 'sort') .
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
