<?php namespace Digbang\L4Backoffice\Actions;

class Collection extends \Illuminate\Support\Collection
{
	protected $factory;

	function __construct(Factory $factory)
	{
		$this->factory = $factory;
	}


}
