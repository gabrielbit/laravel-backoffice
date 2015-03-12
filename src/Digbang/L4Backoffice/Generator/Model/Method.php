<?php namespace Digbang\L4Backoffice\Generator\Model;

class Method
{
	private $apiMethod;
	private $params;

	public function __construct($apiMethod, $params)
	{
		$this->apiMethod = $apiMethod;
		$this->params    = $params;
	}

	public function apiMethod()
	{
		return $this->apiMethod;
	}

	public function params()
	{
		return $this->params;
	}
}
