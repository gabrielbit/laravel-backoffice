<?php namespace Digbang\L4Backoffice\Filters;

use Illuminate\Support\Collection as LaravelCollection;

class Collection extends LaravelCollection
{
    public function text($name, $label = null, $options = [])
    {
	    $filter = new Text($name, $label, $options);

	    $this->push($filter);

        return $this;
    }

    public function dropdown($name, $label = null, $data = [], $options = [])
    {
	    $filter = new DropDown($name, $label, $data, $options);

	    $this->push($filter);

        return $this;
    }

	public function find($name)
	{
		return $this->first(function($key, FilterInterface $filter) use ($name){
			return $filter->name() == $name;
		});
	}

	public function __call($name, $args)
	{
		$this->each(function(FilterInterface $filter) use ($name, $args){
			if (method_exists($filter, $name)) {
				call_user_func_array([$filter, $name], $args);
			}
		});

		return $this;
	}
}
