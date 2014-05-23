<?php namespace Digbang\L4Backoffice\Actions;

use Illuminate\View\Environment as ViewFactory;

class Factory
{
	protected $viewFactory;

	public function __construct(ViewFactory $viewFactory)
	{
		$this->viewFactory = $viewFactory;
	}

    public function link($target, $label = null, $options = [])
    {
        $link = new Link($this->viewFactory);

	    $link->setTarget($target);
	    $link->setLabel($label ?: $target);
	    $link->setOptions($options);

	    return $link;
    }

    public function form($target, $label, $options = [])
    {
	    $form = new Form($this->viewFactory);

	    $form->setTarget($target);
	    $form->setLabel($label);
	    $form->setOptions($options);

	    return $form;
    }
}
