<?php namespace Digbang\L4Backoffice\Forms;

use Digbang\L4Backoffice\Inputs\Collection;
use Digbang\L4Backoffice\Inputs\File;
use Illuminate\Session\Store;
use Illuminate\Support\Contracts\RenderableInterface;
use Illuminate\Support\MessageBag;
use Digbang\L4Backoffice\Actions\Form as FormAction;

class Form implements RenderableInterface
{
	protected $view;
	protected $session;
	protected $collection;
	protected $form;
	protected $cancelAction;
	protected $options;

	function __construct($view, FormAction $form, Collection $collection, Store $session, $cancelAction, $options = [])
	{
		$this->view = $view;
		$this->form = $form;
		$this->collection = $collection;
		$this->cancelAction = $cancelAction ?: array_get($_SERVER, 'HTTP_REFERER');
		$this->session = $session;
		$this->options = $options;
	}

	public function inputs()
    {
        return $this->collection;
    }

	/**
	 * Get the evaluated contents of the object.
	 *
	 * @return string
	 */
	public function render()
	{
		return \View::make($this->view, [
			'label'        => $this->form->label(),
			'formOptions'  => $this->buildOptions($this->form->target(), $this->form->method(), $this->options),
			'inputs'       => $this->collection,
			'cancelAction' => $this->cancelAction,
			'errors'       => $this->session->get('errors') ?: new MessageBag()
		]);
	}

	public function hasFile()
	{
		foreach ($this->collection as $input)
		{
			if ($input instanceof File)
			{
				return true;
			}
		}

		return false;
	}

	protected function buildOptions($action, $method, $options = [])
	{
		$options['url'] = $action;
		$options['method'] = $method;
		$options['files'] = $this->hasFile();

		return array_add($options, 'class', 'form-horizontal form-bordered');
	}
}
