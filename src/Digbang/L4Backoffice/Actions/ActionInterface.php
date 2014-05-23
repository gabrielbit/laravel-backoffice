<?php namespace Digbang\L4Backoffice\Actions;

use Digbang\L4Backoffice\Controls\ControlInterface;

interface ActionInterface extends ControlInterface
{
	/**
	 * The target URL this action points to.
	 * @return string
	 */
	public function target();
}