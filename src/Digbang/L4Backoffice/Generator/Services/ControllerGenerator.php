<?php namespace Digbang\L4Backoffice\Generator\Services;
use Digbang\L4Backoffice\Generator\Model\ControllerInput;
use Digbang\L4Backoffice\Support\Collection;
use Doctrine\DBAL\Schema\ForeignKeyConstraint;

/**
 * Class ControllerGenerator
 * @package Digbang\L4Backoffice\Generator\Services
 */
class ControllerGenerator
{
	protected $generator;
	protected $modelFinder;

	function __construct(Generator $generator, ModelFinder $modelFinder)
	{
		$this->generator = $generator;
		$this->modelFinder = $modelFinder;
	}

	public function generate($tableName, $templatePath, $controllersDirPath, $namespace, $entityNamespace)
	{
		$className = \Str::studly(\Str::singular($tableName));
		$dependencies = [];

		$columns = $this->modelFinder->columns($tableName);
		$foreignKeys = $this->modelFinder->foreignKeys($tableName);

		foreach ($columns as $columnDecorator)
		{
			/* @var $column \Doctrine\DBAL\Schema\Column */
			$column = $columnDecorator->getColumn();

			/* @var $foreignKey \Doctrine\DBAL\Schema\ForeignKeyConstraint */
			$foreignKey = $foreignKeys->first(function($key, ForeignKeyConstraint $foreignKey) use ($column){
				return in_array($column->getName(), $foreignKey->getLocalColumns());
			});

			if ($foreignKey)
			{
				foreach ($foreignKey->getLocalColumns() as $localColumn)
				{
					$dependencies[$localColumn] = [
						'columns' => new Collection($this->modelFinder->columns($foreignKey->getForeignTableName())),
						'table' => $foreignKey->getForeignTableName()
					];
				}
			}
		}

		$destinationPath = $controllersDirPath . DIRECTORY_SEPARATOR . $className . 'Controller.php';

		$this->generator->make(
			$templatePath,
			$destinationPath,
			new ControllerInput(
				$namespace,
				$tableName,
				$entityNamespace,
				$className,
				$columns,
				$dependencies
			)
		);
	}
}