<?php namespace Digbang\L4Backoffice\Forms;

use Digbang\L4Backoffice\Inputs\Collection;

class Form
{
	protected $collection;

	function __construct(Collection $collection)
	{
		$this->collection = $collection;
	}

	public function inputs()
    {
        return $this->collection;
    }
}
