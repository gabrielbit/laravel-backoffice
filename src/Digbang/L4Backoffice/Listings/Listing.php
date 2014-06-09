<?php namespace Digbang\L4Backoffice\Listings;

use Digbang\L4Backoffice\Support\Collection;
use Illuminate\Support\Contracts\RenderableInterface;
use Digbang\L4Backoffice\Filters\Collection as FilterCollection;
use Digbang\L4Backoffice\Actions\Collection as ActionCollection;
use Countable;

class Listing implements RenderableInterface, Countable
{
	protected $view = 'l4-backoffice::listing';

	/**
	 * @var \Digbang\L4Backoffice\Filters\Collection
	 */
	protected $filters;

	/**
	 * @var ColumnCollection
	 */
	protected $columns;

	/**
	 * @var \Digbang\L4Backoffice\Actions\Collection
	 */
	protected $actions;

	/**
	 * @var \Digbang\L4Backoffice\Actions\Collection
	 */
	protected $bulkActions;

	/**
	 * @var \Digbang\L4Backoffice\Support\Collection
	 */
	protected $collection;

	function __construct(Collection $collection, FilterCollection $filters, ActionCollection $actionCollection)
	{
		$this->collection = $collection;
		$this->filters = $filters;
		$this->bulkActions = $actionCollection;
	}

	/**
	 * @param string $view
	 */
	public function setView($view)
	{
		$this->view = $view;
	}

	/**
	 * @return string
	 */
	public function getView()
	{
		return $this->view;
	}

	/**
	 * @param ColumnCollection $columnCollection
	 * @return Listing $this
	 */
	public function columns(ColumnCollection $columnCollection)
	{
		$this->columns = $columnCollection;

		return $this;
	}

	public function filters($name = null)
	{
		if (!$name)
		{
			return $this->filters;
		}

		return $this->filters->find($name);
	}

	public function render()
	{
		return \View::make($this->view, [
			'columns' => $this->columns->visible(),
			'items'   => $this->collection,
			'filters' => $this->filters->all(),
			'actions' => $this->actions()
		]);
	}

    public function fill($elements)
    {
        foreach ($elements as $element)
        {
	        $this->collection->push($this->makeRow($element));
        }

	    return $this;
    }

	/**
	 * @param $element
	 * @return array
	 * @throws \InvalidArgumentException
	 */
	protected function makeRow($element)
	{
		$row = [];

		foreach ($this->columns as $column)
		{
			/* @var $column \Digbang\L4Backoffice\Listings\Column */
			$id = $column->getId();

			if (!array_key_exists($id, $element))
			{
				throw new \InvalidArgumentException("Column $id not defined.");
			}

			$row[$id] = $element[$id];
		}

		return $row;
	}

	public function setActions(ActionCollection $actions)
	{
		$this->actions = $actions;
	}

	public function actions()
	{
		return $this->actions;
	}

	public function setBulkActions(ActionCollection $actions)
	{
		$this->bulkActions = $actions;
	}

	public function bulkActions()
	{
		return $this->bulkActions;
	}

	/**
	 * Count elements of an object
	 * @link http://php.net/manual/en/countable.count.php
	 * @return int The custom count as an integer.
	 * The return value is cast to an integer.
	 */
	public function count()
	{
		return $this->collection->count();
	}
}
