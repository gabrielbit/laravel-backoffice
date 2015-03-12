<?php namespace Digbang\L4Backoffice\Generator\Model;

class ClassName
{
	private $fqcn;

	/**
	 * @param mixed $fqcn
	 */
	public function setFqcn($fqcn)
	{
		$this->fqcn = $fqcn;
	}

	/**
	 * @return string
	 */
	public function fqcn()
	{
		return $this->fqcn;
	}

	/**
	 * @return string
	 */
	public function basename()
	{
		return class_basename($this->fqcn);
	}
}
