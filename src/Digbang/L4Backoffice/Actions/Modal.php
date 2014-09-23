<?php namespace Digbang\L4Backoffice\Actions;

use Digbang\L4Backoffice\Controls\ControlInterface;
use Illuminate\Support\Collection as LaravelCollection;

class Modal extends Action implements ActionInterface
{
	protected $form;

	function __construct($form, ControlInterface $control, $icon = null)
	{
		parent::__construct($control, '#', $icon);
		$this->form = $form;
	}

	public function render()
	{
		$uniqid = uniqid('form_');

		$options = $this->control->options();
		$options['data-toggle'] = "modal";
		$options['data-target'] = "#$uniqid";

		return parent::render()->with(
			[
				'form'   => $this->form,
				'uniqid' => $uniqid
			]
		);
	}

	public function renderWith($row)
	{
		$uniqid = uniqid('form_');

		$options = $this->control->options();
		$options['data-toggle'] = "modal";
		$options['data-target'] = "#$uniqid";

		if ($rendered = parent::renderWith($row))
		{
			$form = $this->form;

			if ($form instanceof \Closure)
			{
				$form = $form(new LaravelCollection($row));
			}

			return $rendered->with(
				[
					'form'   => $form,
					'uniqid' => $uniqid
				]
			);
		}

		return $rendered;
	}
}
