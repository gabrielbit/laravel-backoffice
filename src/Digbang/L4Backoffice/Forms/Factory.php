<?php namespace Digbang\L4Backoffice\Forms;

use Digbang\L4Backoffice\Inputs\Factory as InputFactory;

class Factory
{
	protected $inputFactory;

	function __construct(InputFactory $inputFactory)
	{
		$this->inputFactory = $inputFactory;
	}

	public function make()
    {
        return new Form($this->inputFactory->collection());
    }
}
