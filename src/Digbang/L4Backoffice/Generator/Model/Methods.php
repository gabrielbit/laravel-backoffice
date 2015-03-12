<?php namespace Digbang\L4Backoffice\Generator\Model;

/**
 * Class Methods
 *
 * @package Digbang\L4Backoffice\Generator\Model
 * @method bool hasList()
 * @method bool hasCreate()
 * @method bool hasRead()
 * @method bool hasUpdate()
 * @method bool hasDelete()
 * @method bool hasExport()
 * @method array list()
 * @method array create()
 * @method array read()
 * @method array update()
 * @method array delete()
 * @method array export()
 *
 */
class Methods
{
	/**
	 * @type array
	 */
	private $methods = [];

	/**
	 * @param string $method
	 * @param string $apiMethod
	 * @param array  $params
	 *
	 * @return $this
	 */
	public function add($method, $apiMethod, $params)
	{
		$params = array_map(function($type, $name){
			return new Parameter($type, new StrDecorator($name));
		}, $params);

		$this->methods[strtolower($method)] = compact('apiMethod', 'params');

		return $this;
	}

	public function __call($name, $args)
	{
		if (array_key_exists($name, $this->methods))
		{
			return $this->methods[$name];
		}

		if (strpos($name, 'has') === 0)
		{
			$hasWhat = strtolower(substr($name, 3));
			return array_key_exists($hasWhat, $this->methods);
		}

		throw new \BadMethodCallException("Method $name does not exist.");
	}
}
