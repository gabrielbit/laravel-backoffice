<?php

namespace Digbang\L4Backoffice;

use Illuminate\Support\Collection;

class ColumnCollection extends Collection
{
    public function hide($id)
    {
        $this->each(function(Column $column) use ($id) {
	        if ($column->getId() == $id) {
		        $column->setHidden(true);
	        }
        });
    }

    public function visible()
    {
        return $this->filter(function(Column $column){
	        return $column->isHidden() === false;
        });
    }

    public function hidden()
    {
        return $this->filter(function(Column $column){
	        return $column->isHidden() === true;
        });
    }
}
