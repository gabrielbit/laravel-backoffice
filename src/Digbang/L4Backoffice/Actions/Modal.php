<?php namespace Digbang\L4Backoffice\Actions;

use Digbang\L4Backoffice\Controls\ControlInterface;
use Digbang\L4Backoffice\Forms\Form as GenericForm;

class Modal extends Action implements ActionInterface
{
	protected $form;
	protected $uniqid;

	function __construct(GenericForm $form, $uniqid, ControlInterface $control, $icon = null)
	{
		parent::__construct($control, '#', $icon);

		$this->form = $form;
		$this->uniqid = $uniqid;
	}

	public function render()
	{
		return parent::render()->with([
			'form'   => $this->form,
			'uniqid' => $this->uniqid
		]);
	}
}
