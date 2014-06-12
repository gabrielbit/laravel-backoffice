<?php namespace Digbang\L4Backoffice\Forms;

use Digbang\L4Backoffice\Inputs\Factory as InputFactory;
use Digbang\L4Backoffice\Actions\Factory as ActionFactory;
use Illuminate\Session\Store;

class Factory
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
	        'l4-backoffice::form',
	        $this->actionFactory->form($target, $label, $method, []),
	        $this->inputFactory->collection(),
	        $this->session,
	        $cancelAction,
	        $options
        );
    }
}
