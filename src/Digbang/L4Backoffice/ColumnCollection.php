<?php namespace Digbang\L4Backoffice;

use Digbang\L4Backoffice\Support\Collection;

class ColumnCollection extends Collection
{
	public function __construct(array $items = array())
	{
		foreach ($items as $id => $label)
		{
			if (!is_string($id))
			{
				$id = $label;
			}

			$this->push(new Column($id, $label));
		}
	}

	public function hide($id)
    {
        $this->each(function(Column $column) use ($id) {
	        if ($column->getId() == $id) {
		        $column->setHidden(true);
	        }
        });

	    return $this;
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
