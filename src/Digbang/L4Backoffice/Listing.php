<?php

namespace Digbang\L4Backoffice;

use Illuminate\Support\Collection;
use Illuminate\Support\Contracts\ArrayableInterface;
use Illuminate\Support\Contracts\RenderableInterface;
use Digbang\L4Backoffice\Filters\Collection as FilterCollection;
use Illuminate\View\Environment as ViewFactory;

class Listing extends Collection implements RenderableInterface
{
	protected $view = 'l4-backoffice::listing';

	/**
	 * @var \Illuminate\View\Environment
	 */
	protected $viewFactory;

	protected $filters;

	function __construct(ViewFactory $viewFactory, FilterCollection $filters)
	{
		$this->viewFactory = $viewFactory;
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

	public function filters()
	{

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
		return $this->viewFactory->make($this->view, $this->toArray())->render();
	}
}
