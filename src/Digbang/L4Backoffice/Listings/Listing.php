<?php namespace Digbang\L4Backoffice\Listings;

use Countable;
use Digbang\L4Backoffice\Actions\Collection as ActionCollection;
use Digbang\L4Backoffice\Controls\ControlInterface;
use Digbang\L4Backoffice\Extractors\ValueExtractorFacade;
use Digbang\L4Backoffice\Inputs\Collection as FilterCollection;
use Digbang\L4Backoffice\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Contracts\RenderableInterface;

class Listing implements RenderableInterface, Countable
{
	/**
	 * @type string
	 */
	protected $view = 'backoffice::listing';

	/**
	 * @var FilterCollection
	 */
	protected $filters;

	/**
	 * @var ColumnCollection
	 */
	protected $columns;

	/**
	 * @var ActionCollection
	 */
	protected $actions;

	/**
	 * @var ActionCollection
	 */
	protected $rowActions;

	/**
	 * @var ActionCollection
	 */
	protected $bulkActions;

	/**
	 * @var Collection
	 */
	protected $rows;

	/**
	 * @type ControlInterface
	 */
	protected $control;

	/**
	 * @type Paginator
	 */
	protected $paginator;

	/**
	 * @type ValueExtractorFacade
	 */
	protected $valueExtractor;

	/**
	 * @type string
	 */
	protected $resetAction;

	/**
	 * @type Request
	 */
	protected $request;

	/**
	 * @param ControlInterface     $control
	 * @param Collection           $rows
	 * @param FilterCollection     $filters
	 * @param ValueExtractorFacade $valueExtractor
	 * @param Request|null         $request
	 */
	public function __construct(ControlInterface $control, Collection $rows, FilterCollection $filters, ValueExtractorFacade $valueExtractor, Request $request = null)
	{
		$this->control = $control;
		$this->rows    = $rows;
		$this->filters = $filters;
		$this->valueExtractor = $valueExtractor;
		$this->request = $request;
	}

	/**
	 * @param ColumnCollection $columnCollection
	 * @return ColumnCollection
	 */
	public function columns(ColumnCollection $columnCollection = null)
	{
		if ($columnCollection)
		{
			$this->columns = $columnCollection;
		}

		return $this->columns;
	}

	/**
	 * @return Collection
	 */
	public function rows()
	{
		return $this->rows;
	}

	/**
	 * @param string|null $name
	 *
	 * @return FilterCollection|\Digbang\L4Backoffice\Inputs\InputInterface|null
	 */
	public function filters($name = null)
	{
		if (!$name)
		{
			return $this->filters;
		}

		return $this->filters->find($name);
	}

	/**
	 * @return \Illuminate\View\View
	 */
	public function render()
	{
		return $this->control->render()->with([
			'columns'     => $this->columns->visible(),
			'items'       => $this->rows,
			'filters'     => $this->filters->all(),
			'resetAction' => $this->getResetAction(),
			'actions'     => $this->actions(),
			'rowActions'  => $this->rowActions(),
			'bulkActions' => $this->bulkActions(),
			'paginator'   => $this->paginator
		]);
	}

	/**
	 * @param array|\Traversable $elements
	 *
	 * @return $this
	 */
    public function fill($elements)
    {
        $this->addElements($elements);

	    if ($elements instanceof Paginator)
	    {
		    $this->paginator = $elements;
	    }

	    return $this;
    }

	/**
	 * @param ActionCollection $actions
	 */
	public function setActions(ActionCollection $actions)
	{
		$this->actions = $actions;
	}

	/**
	 * @return ActionCollection
	 */
	public function actions()
	{
		return $this->actions;
	}

	/**
	 * @param ActionCollection $rowActions
	 */
	public function setRowActions(ActionCollection $rowActions)
	{
		$this->rowActions = $rowActions;
	}

	/**
	 * @return ActionCollection
	 */
	public function rowActions()
	{
		return $this->rowActions;
	}

	/**
	 * @param ActionCollection $actions
	 */
	public function setBulkActions(ActionCollection $actions)
	{
		$this->bulkActions = $actions;
	}

	/**
	 * @return ActionCollection
	 */
	public function bulkActions()
	{
		return $this->bulkActions;
	}

	/**
	 * @param string $url
	 */
	public function setResetAction($url)
	{
		$this->resetAction = $url;
	}

	/**
	 * @return null|string
	 */
	public function getResetAction()
	{
		return $this->resetAction ?: (isset($this->request) ? $this->request->url() : null);
	}

	/**
	 * Count elements of an object
	 * @link http://php.net/manual/en/countable.count.php
	 * @return int The custom count as an integer.
	 * The return value is cast to an integer.
	 */
	public function count()
	{
		return $this->rows->count();
	}

	/**
	 * @param string $view
	 */
	public function setView($view)
	{
		$this->view = $view;
	}

	/**
	 * @param array|Collection $elements
	 */
	protected function addElements($elements)
	{
		foreach ($elements as $element)
		{
			$this->rows->push($this->makeRow($element));
		}
	}

	/**
	 * @param mixed $element
	 * @return array
	 * @throws \InvalidArgumentException
	 */
	protected function makeRow($element)
	{
		$row = [];

		foreach ($this->columns as $column)
		{
			/* @var $column \Digbang\L4Backoffice\Listings\Column */
			$row[$column->getId()] = $this->valueExtractor->extract($element, $column->getId());
		}

		return $row;
	}
}
