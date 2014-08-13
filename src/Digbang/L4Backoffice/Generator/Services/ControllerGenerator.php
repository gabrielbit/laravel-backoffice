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
		);
	}
}