<?php namespace Digbang\L4Backoffice\Listings;

class ColumnFactory
{
    public function make($data = [])
    {
	    $columns = new ColumnCollection();

	    foreach ($data as $id => $label)
	    {
		    $columns->push(new Column(is_string($id) ? $id : $label, $label));
	    }

	    return $columns;
    }
}
