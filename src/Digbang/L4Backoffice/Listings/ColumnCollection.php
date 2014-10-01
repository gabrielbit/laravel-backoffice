<?php namespace Digbang\L4Backoffice\Listings;

use Digbang\L4Backoffice\Support\Collection;

class ColumnCollection extends Collection
{
	public function __construct(array $items = array())
	{
		$this->parseItems($items);
	}

	public function hide($ids)
    {
	    return $this->toggleHidden($ids, true);
    }

	public function show($ids)
	{
		return $this->toggleHidden($ids, false);
	}

	protected function toggleHidden($ids, $isHidden)
	{
		foreach ((array) $ids as $id)
		{
			$this->each(function(Column &$column) use ($id, $isHidden) {
				if ($column->getId() == $id) {
					$column->setHidden($isHidden);
				}
			});
		}

		return $this;
	}

	public function sortable($ids)
	{
		$ids = (array) $ids;

		$this->each(function(Column &$column) use ($ids) {
			$column->setSortable(in_array($column->getId(), $ids));
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

	/**
	 * @param array $items
	 */
	protected function parseItems(array $items)
	{
		foreach ($items as $id => $label)
		{
			$column = $label;

			if (!$column instanceof Column)
			{
				if (!is_string($id))
				{
					$id = $label;
				}

				$column = new Column($id, $label);
			}

			$this->push($column);
		}
	}

	public function setAccessor($id, $accessor)
	{
		$this->each(function(Column &$column) use ($id, $accessor) {
			if ($column->getId() == $id){
				$column->setAccessor($accessor);
			}
		});

		return $this;
	}
}
