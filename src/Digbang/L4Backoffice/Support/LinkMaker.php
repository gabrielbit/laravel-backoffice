<?php namespace Digbang\L4Backoffice\Support;

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

	public function sort($column, $sense = self::SORT_SENSE_ASC)
	{
		return $this->request->url() . '?' .
			http_build_query(array_merge(
				$this->request->all(),
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
}
