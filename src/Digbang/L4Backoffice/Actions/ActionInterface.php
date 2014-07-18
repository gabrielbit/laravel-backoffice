<?php namespace Digbang\L4Backoffice\Actions;

interface ActionInterface
{
	/**
	 * The target URL this action points to.
	 * @return string
	 */
	public function target();
}