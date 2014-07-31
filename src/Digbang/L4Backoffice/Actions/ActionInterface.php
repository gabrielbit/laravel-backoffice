<?php namespace Digbang\L4Backoffice\Actions;

interface ActionInterface
{
	/**
	 * The target URL this action points to.
	 * @return string
	 */
	public function target();

	/**
	 * Is the action the current active URL?
	 * @return bool
	 */
	public function isActive();
}