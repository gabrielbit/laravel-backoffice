<?php namespace Digbang\L4Backoffice\Actions;

use Digbang\L4Backoffice\Support\Collection as DigbangCollection;

class Collection extends DigbangCollection
{
	protected $factory;

	function __construct(Factory $factory)
	{
		$this->factory = $factory;
	}

	public function link($target, $label = null, $options = [])
	{
		$this->push($this->factory->link($target, $label, $options));

		return $this;
	}

	public function form($target, $label, $options = [])
	{
		$this->push($this->factory->link($target, $label, $options));

		return $this;
	}
}
