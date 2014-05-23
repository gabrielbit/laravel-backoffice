<?php namespace Digbang\L4Backoffice\Filters;

class Factory
{
    public function text($name, $label = null, $options = [])
    {
        return new Text($name, $label, $options);
    }

	public function dropdown($name, $label = null, $data = [], $options = [])
    {
	    return new DropDown($name, $label, $data, $options);
    }
}
