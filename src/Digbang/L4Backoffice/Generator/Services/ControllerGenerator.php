<?php namespace Digbang\L4Backoffice\Generator\Services;

use Digbang\L4Backoffice\Generator\Model\ControllerInput;

/**
 * Class ControllerGenerator
 * @package Digbang\L4Backoffice\Generator\Services
 */
class ControllerGenerator
{
	/**
	 * @type array
	 */
	private $validMethods = [
		'index', 'create', 'read', 'update', 'delete', 'export'
	];

	/**
	 * @type Generator
	 */
	private $generator;

	/**
	 * @type string
	 */
	private $templatePath;

	/**
	 * @type string
	 */
	private $controllerDir;

	/**
	 * @type string
	 */
	private $controllerNamespace;

	/**
	 * @type string
	 */
	private $api;

	/**
	 * @type array
	 */
	private $methods = [];

	/**
	 * @type ControllerInput
	 */
	private $controllerInput;

	/**
	 * @param Generator       $generator
	 * @param ControllerInput $controllerInput
	 */
	public function __construct(Generator $generator, ControllerInput $controllerInput)
	{
		$this->generator = $generator;
		$this->controllerInput = $controllerInput;
	}

	/**
	 * @param string $templatePath
	 *
	 * @return $this
	 */
	public function fromTemplate($templatePath)
	{
		if (! file_exists($templatePath))
		{
			throw new \UnexpectedValueException("Given template file $templatePath does not exist.");
		}

		$this->templatePath = $templatePath;

		return $this;
	}

	/**
	 * @param string $controllerDir
	 *
	 * @return $this
	 */
	public function toDir($controllerDir)
	{
		if (! is_dir($controllerDir))
		{
			throw new \UnexpectedValueException("Given directory $controllerDir is not a directory.");
		}

		$this->controllerDir = $controllerDir;

		return $this;
	}

	public function inNamespace($controllerNamespace)
	{
		$this->controllerNamespace = $controllerNamespace;

		return $this;
	}

	/**
	 * @param string $api
	 *
	 * @return $this
	 */
	public function withApi($api)
	{
		$this->api = $api;
		$this->methods = [];

		return $this;
	}

	/**
	 * @param string $method
	 * @param string $apiMethod
	 * @param array  $params
	 *
	 * @return $this
	 */
	public function addMethod($method, $apiMethod, array $params = [])
	{
		$method = strtolower($method);
		if (! in_array($method, $this->validMethods))
		{
			throw new \UnexpectedValueException("Method $method does not exist.");
		}

		$this->methods[$method] = [$apiMethod, $params];

		return $this;
	}

	public function generate()
	{
		$this->controllerInput->reset();

		$className = preg_replace('/BackofficeApi$/', '', class_basename($this->api));

		$destinationPath = $this->controllerDir . DIRECTORY_SEPARATOR . $className . 'Controller.php';

		$this->controllerInput->setClassName($className);
		$this->controllerInput->setApi($this->api);
		$this->controllerInput->setNamespace($this->controllerNamespace);

		foreach ($this->methods as $method => $data)
		{
			list($apiMethod, $params) = $data;
			$this->controllerInput->addMethod($method, $apiMethod, $params);
		}

		$this->generator->make(
			$this->templatePath,
			$destinationPath,
			$this->controllerInput
		);

		return trim($this->controllerNamespace, ' \\') . '\\' . $className . 'Controller';
	}
}