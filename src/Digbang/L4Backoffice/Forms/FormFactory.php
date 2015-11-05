<?php namespace Digbang\L4Backoffice\Forms;

use Digbang\L4Backoffice\Inputs\InputFactory as InputFactory;
use Digbang\L4Backoffice\Actions\ActionFactory as ActionFactory;
use Illuminate\Session\Store;

class FormFactory
{
	protected $inputFactory;
	protected $actionFactory;
	protected $session;

	function __construct(InputFactory $inputFactory, ActionFactory $actionFactory, Store $session)
	{
		$this->inputFactory = $inputFactory;
		$this->actionFactory = $actionFactory;
		$this->session = $session;
	}

	public function make($target, $label, $method = 'POST',  $cancelAction = '', $options = [])
    {
        return new Form(
	        'backoffice::form',
	        $this->actionFactory->form($target, $label, $method, []),
	        $this->inputFactory->collection(),
	        $this->session,
	        $cancelAction,
	        $options
        );
    }
}
