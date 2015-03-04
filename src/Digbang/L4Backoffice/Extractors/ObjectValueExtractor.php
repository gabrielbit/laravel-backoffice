<?php namespace Digbang\L4Backoffice\Extractors;

class ObjectValueExtractor implements ValueExtractor
{
	/**
	 * @type array
	 * @internal
	 */
	private $refMethodCache = [];

	/**
	 * @param string $method
	 *
	 * @return \ReflectionMethod
	 * @internal
	 */
	private function __getReflectionMethod($object, $method)
	{
		$class = get_class($object);

		if (! array_key_exists($class, $this->refMethodCache))
		{
			$this->refMethodCache[$class] = [];
		}

		if (! array_key_exists($method, $this->refMethodCache[$class]))
		{
			$this->refMethodCache[$class][$method] = new \ReflectionMethod($class, $method);
		}

		return $this->refMethodCache[$class][$method];
	}

	/**
	 * @param object $element
	 * @param string $key
	 *
	 * @return string the extracted value
	 */
	public function extract($element, $key)
	{
		foreach (['get', 'is'] as $prefix)
		{
			if (method_exists($this, $method = $prefix . studly_case($key)))
			{
				$reflectionMethod = $this->__getReflectionMethod($element, $method);

				if ($reflectionMethod->isPublic())
				{
					return $reflectionMethod->invoke($element);
				}
			}
		}

		throw new \BadMethodCallException("Property '$key' is private or does not exist.");
	}
}
