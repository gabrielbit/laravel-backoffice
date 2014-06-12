<?php namespace Digbang\L4Backoffice\Forms;

use Digbang\L4Backoffice\Controls\ControlFactory;
use Digbang\L4Backoffice\Inputs\Factory as InputFactory;

class Factory
{
	protected $inputFactory;
	protected $controlFactory;

	function __construct(InputFactory $inputFactory, ControlFactory $controlFactory)
	{
		$this->inputFactory = $inputFactory;
		$this->controlFactory = $controlFactory;
	}

	public function make($label, $options = [])
    {
        return new Form(
	        $this->controlFactory->make('l4-backoffice::form', $label, $options),
	        $this->inputFactory->collection()
        );
    }
}
