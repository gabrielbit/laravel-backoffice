<?php namespace Digbang\L4Backoffice;

use Digbang\L4Backoffice\Support\Collection;
use Illuminate\Support\Contracts\RenderableInterface;
use Digbang\L4Backoffice\Filters\Collection as FilterCollection;
use Digbang\L4Backoffice\Actions\Collection as ActionCollection;

class Listing extends Collection implements RenderableInterface
{
	protected $view = 'l4-backoffice::listing';
	protected $filters;
	protected $columns;
	protected $actions;

	function __construct(FilterCollection $filters)
	{
		$this->filters = $filters;
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
			'items' => $this->toArray(),
			'filters' => $this->filters
		])->render();
	}

    public function fill($elements)
    {
        foreach ($elements as $element)
        {
	        $this->push($this->makeRow($element));
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

		foreach ($this->columns->visible() as $column)
		{
			/* @var $column \Digbang\L4Backoffice\Column */
			if (!array_key_exists($column->getId(), $element))
			{
				throw new \InvalidArgumentException("Column {$column->getId()} not defined.");
			}

			$row[$column->getId()] = $element[$column->getId()];
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
}
