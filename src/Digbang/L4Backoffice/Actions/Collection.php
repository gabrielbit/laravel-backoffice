<?php namespace Digbang\L4Backoffice\Actions;

use Digbang\L4Backoffice\Support\Collection as DigbangCollection;

class Collection extends DigbangCollection
{
	protected $factory;

	function __construct(Factory $factory)
	{
		$this->factory = $factory;
	}
}
