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
        return new Action($this->controlFactory->make('l4-backoffice::actions.link', $label, $options), $target);
    }

    public function form($target, $label, $options = [])
    {
	    $options['class'] = $this->uniqueClasses(array_get($options, 'class', ''), ['btn-action']);

	    return new Action($this->controlFactory->make('l4-backoffice::actions.form', $label, $options), $target);
    }

	protected function uniqueClasses($current, array $newClasses)
	{
		return implode(' ', array_unique(array_merge(explode(' ', $current), $newClasses)));
	}
}
