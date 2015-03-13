<?php namespace Digbang\L4Backoffice\Actions;

/**
 * Class ActionBuilder
 *
 * @package Digbang\L4Backoffice\Actions
 * @method $this addClass($className)
 * @method $this addRel($rel)
 * @method $this addTarget($target)
 * @method $this addDataConfirm($message)
 */
interface ActionBuilderInterface
{
	/**
	 * @param string $target
	 *
	 * @return $this
	 */
	public function to($target);

	/**
	 * @param string $label
	 *
	 * @return $this
	 */
	public function labeled($label);

	/**
	 * @param string $view
	 *
	 * @return $this
	 */
	public function view($view);

	/**
	 * @param string $icon
	 *
	 * @return $this
	 */
	public function icon($icon);

	/**
	 * @param string $attribute
	 * @param string $value
	 *
	 * @return $this
	 */
	public function add($attribute, $value);

	/**
	 * @return Action
	 */
	public function asLink();

	/**
	 * @param string $method
	 * @return Action
	 */
	public function asForm($method = 'POST');
}