<?php namespace Digbang\L4Backoffice\Actions;

use Digbang\L4Backoffice\Controls\ControlFactory;
use Digbang\L4Backoffice\Support\Collection as DigbangCollection;
use Illuminate\Http\Request;

class ActionFactory
{
	protected $controlFactory;
	protected $request;

	function __construct(ControlFactory $controlFactory, Request $request = null)
	{
		$this->controlFactory = $controlFactory;
		$this->request = $request;
	}

	public function link($target, $label = null, $options = [], $view = null, $icon = null)
    {
	    $view = $view ?: 'l4-backoffice::actions.link';
        $action = new Action($this->controlFactory->make($view, $label, $options), $target, $icon);

	    if ($this->request && ! $target instanceof \Closure)
	    {
		    $action->setActive($this->request->url() == $target);
	    }

	    return $action;
    }

    public function form($target, $label, $method = 'POST', $options = [], $view = null)
    {
	    $view = $view ?: 'l4-backoffice::actions.form';
	    $options['class'] = $this->uniqueClasses(array_get($options, 'class', ''), ['btn-action']);

	    return new Form(
		    $this->controlFactory->make($view, $label, $options),
		    $target,
		    $method);
    }

	public function modal($form, $label, $options = [], $icon = null)
	{
		return new Modal(
			$form,
			$this->controlFactory->make('l4-backoffice::actions.modal', $label, $options),
			$icon
		);
	}

	public function collection()
	{
		return new Collection($this, new DigbangCollection());
	}

	public function dropdown($label, $options = [], $view = 'l4-backoffice::actions.dropdown', $icon = null)
	{
		$action = new Composite(
			$this->controlFactory->make($view, $label, $options),
			$this,
			new DigbangCollection(),
			$icon
		);

		return $action;
	}

	protected function uniqueClasses($current, array $newClasses)
	{
		return implode(' ', array_unique(array_merge(explode(' ', $current), $newClasses)));
	}
}
