<?php namespace Digbang\L4Backoffice\Actions;

use Digbang\L4Backoffice\Controls\ControlTrait;

trait ActionTrait {
	use ControlTrait;

	protected $target;

	public function setTarget($target)
	{
		$this->target = $target;
	}

	public function target()
	{
		return $this->target;
	}
}