<?php

namespace Digbang\L4Backoffice;

use Illuminate\Support\Collection;
use Illuminate\Support\Contracts\ArrayableInterface;
use Illuminate\Support\Contracts\RenderableInterface;
use Digbang\L4Backoffice\Filters\Collection as FilterCollection;

class Listing extends Collection implements RenderableInterface
{
	protected $view = 'l4-backoffice::listing';
	protected $filters;

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
		return $this->mergeInto($columnCollection);
	}

	public function filters($name = null)
	{
		if (!$name)
		{
			return $this->filters;
		}

		return $this->filters->find($name);
	}

	public function mergeInto($items)
	{
		if ($items instanceof Collection)
		{
			$items = $items->all();
		}
		elseif ($items instanceof ArrayableInterface)
		{
			$items = $items->toArray();
		}

		$this->items = array_merge($this->items, $items);

		return $this;
	}

	public function render()
	{
		return \View::make($this->view, $this->toArray())->render();
	}
}
