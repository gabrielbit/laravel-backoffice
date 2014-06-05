<?php namespace Digbang\L4Backoffice\Controls;

use Illuminate\Support\Contracts\RenderableInterface;

interface ControlInterface extends RenderableInterface
{
	/**
	 * A text that will be printed to the user.
	 * @return string
	 */
	public function label();

	/**
	 * The control HTML options. May access a specific one through parameter, null should return all of them.
	 * @param string $name
	 * @return \Illuminate\Support\Collection
	 */
	public function options($name = null);

	/**
	 * The view that will be rendered. Controls always render a view of some sort.
	 * @return \Illuminate\View\View
	 */
	public function view();
}