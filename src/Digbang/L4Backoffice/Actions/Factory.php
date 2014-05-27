<?php namespace Digbang\L4Backoffice\Actions;

class Factory
{
    public function link($target, $label = null, $options = [])
    {
        $link = new Link();

	    $link->setTarget($target);
	    $link->setLabel($label ?: $target);
	    $link->setOptions($options);

	    return $link;
    }

    public function form($target, $label, $options = [])
    {
	    $form = new Form();

	    $form->setTarget($target);
	    $form->setLabel($label);
	    $form->setOptions($options);

	    return $form;
    }
}
