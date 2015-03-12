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

	public function __construct(MethodCollection $methods, StrDecorator $str, ClassName $api)
	{
		$this->prototypes = [
			'methods'   => $methods,
			'className' => $str,
			'api'       => $api
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

	public function api()
	{
		return $this->data['api'];
	}

	public function methods()
	{
		return $this->data['methods'];
	}

	public function theNamespace()
	{
		return $this->data['namespace'];
	}

	public function className()
	{
		return $this->data['className'];
	}
}
