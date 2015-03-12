<?php namespace Digbang\L4Backoffice\Generator\Model;

class MethodCollection
{
	/**
	 * @type array
	 */
	private $methods = [];

	/**
	 * @param string $method
	 * @param string $apiMethod
	 * @param array  $parameters
	 *
	 * @return $this
	 */
	public function add($method, $apiMethod, $parameters)
	{
		$params = [];

		foreach ($parameters as $name => $type)
		{
			$params[] = new Parameter(new StrDecorator($name), $type);
		}

		$this->methods[strtolower($method)] = new Method($apiMethod, $params);

		return $this;
	}

	public function hasCreate()
	{
		return $this->has('create');
	}

	public function hasRead()
	{
		return $this->has('read');
	}

	public function hasUpdate()
	{
		return $this->has('update');
	}

	public function hasDelete()
	{
		return $this->has('delete');
	}

	public function hasIndex()
	{
		return $this->has('index');
	}

	public function hasExport()
	{
		return $this->has('export');
	}

	public function create()
	{
		return $this->get('create');
	}

	public function read()
	{
		return $this->get('read');
	}

	public function update()
	{
		return $this->get('update');
	}

	public function delete()
	{
		return $this->get('delete');
	}

	public function index()
	{
		return $this->get('index');
	}

	public function export()
	{
		return $this->get('export');
	}

	public function has($hasWhat)
	{
		return array_key_exists($hasWhat, $this->methods);
	}

	public function get($name)
	{
		if (array_key_exists($name, $this->methods))
		{
			return $this->methods[$name];
		}
	}
}
