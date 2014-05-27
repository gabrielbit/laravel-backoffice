<?php

namespace Digbang\L4Backoffice;

use Digbang\L4Backoffice\Support\Collection;
use Illuminate\Support\Contracts\RenderableInterface;
use Digbang\L4Backoffice\Filters\Collection as FilterCollection;

class Listing extends Collection implements RenderableInterface
{
	protected $view = 'l4-backoffice::listing';
	protected $filters;
	protected $columns;

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
		return \View::make($this->view, $this->toArray())->render();
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

		foreach (array_keys($this->columns->toArray()) as $columnName)
		{
			if (!array_key_exists($columnName, $element))
			{
				throw new \InvalidArgumentException("Column $columnName not defined.");
			}

			$row[$columnName] = $element[$columnName];
		}

		return $row;
	}
}
