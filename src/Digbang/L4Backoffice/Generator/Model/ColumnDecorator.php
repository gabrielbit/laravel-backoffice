<?php namespace Digbang\L4Backoffice\Generator\Model;
use Doctrine\DBAL\Schema\Column;

/**
 * Class ColumnDecorator
 * @package Digbang\L4Backoffice\Generator\Model
 */
class ColumnDecorator
{
	protected $column;

	function __construct(Column $column)
	{
		$this->column = $column;
	}

	public function __toString()
	{
		return (string) $this->column->getName();
	}
}