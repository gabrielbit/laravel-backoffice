<?php namespace Digbang\L4Backoffice\Actions;

use Digbang\L4Backoffice\Controls\ControlFactory;

class Factory
{
	protected $controlFactory;

	function __construct(ControlFactory $controlFactory)
	{
		$this->controlFactory = $controlFactory;
	}

	public function link($target, $label = null, $options = [])
    {
        return new Action($this->controlFactory->make('l4-backoffice::link', $label, $options), $target);
    }

    public function form($target, $label, $options = [])
    {
	    return new Action($this->controlFactory->make('l4-backoffice::form', $label, $options), $target);
    }
}
