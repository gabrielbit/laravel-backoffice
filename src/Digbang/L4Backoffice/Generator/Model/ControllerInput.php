<?php namespace Digbang\L4Backoffice\Generator\Model;

/**
 * Class ControllerInput
 * @package Digbang\L4Backoffice\Generator
 */
class ControllerInput
{
	/**
	 * @type array
	 */
	private $prototypes = [];

	/**
	 * @type array
	 */
	private $data = [];

	public function __construct(Methods $methods, StrDecorator $str, ClassName $api)
	{
		$this->prototypes = [
			'methods' => $methods,
			'str'     => $str,
			'api'     => $api
		];

		$this->reset();
	}

	public function reset()
	{
		$this->data = array_map(function($prototype){
			return clone $prototype;
		}, $this->prototypes);
	}

	public function setClassName($className)
	{
		$this->data['className']->setString($className);
	}

	public function addMethod($method, $apiMethod, $params)
	{
		$this->data['methods']->add($method, $apiMethod, $params);
	}

	public function setApi($api)
	{
		$this->data['api']->setFqcn($api);
	}

	public function setNamespace($namespace)
	{
		$this->data['namespace'] = $namespace;
	}

	public function __call($func, $args)
	{
		if (array_key_exists($func, $this->data))
		{
			return $this->data[$func];
		}

		throw new \BadMethodCallException("Method $func does not exist.");
	}
}