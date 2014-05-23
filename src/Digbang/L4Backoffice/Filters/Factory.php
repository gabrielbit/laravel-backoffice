<?php namespace Digbang\L4Backoffice\Filters;

class Factory
{
	public function text($name, $label = null, $options = [])
    {
        $text = new Text();

	    $text->setName($name);
	    $text->setLabel($label);
	    $text->setOptions($options);

	    return $text;
    }

	public function dropdown($name, $label = null, $data = [], $options = [])
    {
	    $dropdown = new DropDown();

	    $dropdown->setName($name);
	    $dropdown->setLabel($label);
	    $dropdown->setData($data);
	    $dropdown->setOptions($options);

	    return $dropdown;
    }
}
