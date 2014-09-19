<?php namespace Digbang\L4Backoffice\Actions;

use Digbang\L4Backoffice\Controls\ControlInterface;
use Illuminate\Support\Collection as LaravelCollection;

class Modal extends Action implements ActionInterface
{
	protected $form;
	protected $uniqid;

	function __construct($form, $uniqid, ControlInterface $control, $icon = null)
	{
		parent::__construct($control, '#', $icon);

		$this->form   = $form;
		$this->uniqid = $uniqid;
	}

	public function render()
	{
		return parent::render()->with(
			[
				'form'   => $this->form,
				'uniqid' => $this->uniqid
			]
		);
	}

	public function renderWith($row)
	{
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
					'uniqid' => $this->uniqid
				]
			);
		}

		return $rendered;
	}
}
