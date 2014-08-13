<?php namespace Digbang\L4Backoffice\Generator\Services;
use Digbang\L4Backoffice\Generator\Model\ControllerInput;

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

		$columns = $this->modelFinder->columns($tableName);

		$destinationPath = $controllersDirPath . DIRECTORY_SEPARATOR . $className . 'Controller.php';

		$this->generator->make(
			$templatePath,
			$destinationPath,
			new ControllerInput(
				$namespace,
				$tableName,
				$entityNamespace . $className,
				$columns
			)
		/*
			[
				'namespace'              => $namespace,
				'classname'              => $className,
				'snake_classname'        => $tableName,
				'plural_classname'       => $pluralClassname,
				'camel_classname'        => \Str::camel($tableName),
				'full_model'             => $entityNamespace . $className,
				'title_attribute_getter' => array_first($columns, function($key, $value){ return $value != 'id'; }),
				'inputs_into_columns'    => $this->columnsInputs($columns),
				'data_into_columns'      => $this->columnsData($columns),
				'columns'                => $columns,
				'columns_with_labels'    => $this->columnsLabel($columns),
				'columns_hide'           => in_array('id', $columns) ? "'id'" : '',
				'columns_sortable'       => $this->columns($columns),
				'form_inputs'            => $this->formInputs($editableColumns),
				'filters'                => $this->filters($columns)
			]*/
		);
	}
}