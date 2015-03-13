<?php namespace Digbang\L4Backoffice\Actions;

/**
 * Class ActionBuilderWrapper
 *
 * @package Digbang\L4Backoffice\Actions
 * @method $this addClass($className)
 * @method $this addRel($rel)
 * @method $this addTarget($target)
 * @method $this addDataConfirm($message)
 */
class ActionBuilderWrapper implements ActionBuilderInterface
{
	/**
	 * @type ActionBuilder
	 */
	private $actionBuilder;

	/**
	 * @type Collection
	 */
	private $actionCollection;

	/**
	 * @param ActionBuilder $actionBuilder
	 * @param Collection    $collection
	 */
	public function __construct(ActionBuilder $actionBuilder, Collection $collection)
	{
		$this->actionBuilder    = $actionBuilder;
		$this->actionCollection = $collection;
	}

	/**
	 * @param string $target
	 *
	 * @return $this
	 */
	public function to($target)
	{
		$this->actionBuilder->to($target);

		return $this;
	}

	/**
	 * @param string $label
	 *
	 * @return $this
	 */
	public function labeled($label)
	{
		$this->actionBuilder->labeled($label);

		return $this;
	}

	/**
	 * @param string $view
	 *
	 * @return $this
	 */
	public function view($view)
	{
		$this->actionBuilder->view($view);

		return $this;
	}

	/**
	 * @param string $icon
	 *
	 * @return $this
	 */
	public function icon($icon)
	{
		$this->actionBuilder->icon($icon);

		return $this;
	}

	/**
	 * @param string $attribute
	 * @param string $value
	 *
	 * @return $this
	 */
	public function add($attribute, $value)
	{
		$this->actionBuilder->add($attribute, $value);
	}

	/**
	 * @return Action
	 */
	public function asLink()
	{
		$link = $this->actionBuilder->asLink();

		$this->actionCollection->add($link);

		return $link;
	}

	/**
	 * @param string $func
	 * @param array  $args
	 * @return $this
	 */
	public function __call($func, $args)
	{
		call_user_func_array([$this->actionBuilder, $func], $args);

		return $this;
	}

	/**
	 * @param string $method
	 *
	 * @return Action
	 */
	public function asForm($method = 'POST')
	{
		$form = $this->actionBuilder->asForm($method);

		$this->actionCollection->add($form);

		return $form;
	}
}
