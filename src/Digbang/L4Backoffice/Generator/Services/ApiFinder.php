<?php namespace Digbang\L4Backoffice\Generator\Services;

use Nette\Reflection\Method;
use Nette\Reflection\Parameter;

class ApiFinder
{
	/**
	 * @param string $directory
	 *
	 * @return array
	 */
	public function all($directory)
	{
		$this->includeDirectory($directory);

		$apis = [];

		foreach (get_declared_interfaces() as $potentialApi)
		{
			if ($this->isBackofficeApi($potentialApi))
			{
				$apis[] = $potentialApi;
			}
		}

		return $apis;
	}

	/**
	 * @param $directory
	 */
	private function includeDirectory($directory)
	{
		foreach (new \DirectoryIterator($directory) as $file)
		{
			if ($file->isReadable() && $file->getExtension() == 'php')
			{
				include_once $file->getRealPath();
			}
		}
	}

	/**
	 * @param string $potentialApi
	 *
	 * @return boolean
	 */
	private function isBackofficeApi($potentialApi)
	{
		return (boolean) preg_match('/BackofficeApi$/', $potentialApi);
	}

    public function parseMethods($api)
    {
        $refClass = new \ReflectionClass($api);

	    return array_map(function(\ReflectionMethod $reflectionMethod){
		    return $reflectionMethod->getName();
	    }, $refClass->getMethods(\ReflectionMethod::IS_PUBLIC));
    }

    public function getParamsFor($api, $method)
    {
        $refMethod = new Method($api, $method);
	    $parameters = $refMethod->getParameters();

	    $parsedParameters = [];
	    foreach ($parameters as $parameter)
	    {
		    $parsedParameters[$parameter->getName()] = 'mixed';
	    }

	    if ($refMethod->hasAnnotation('param'))
	    {
		    // Hack: getAnnotation('param') doesn't work as expected
		    $annotations = $refMethod->getAnnotations()['param'];

		    foreach ($parameters as $parameter)
		    {
			    foreach ($annotations as $annotation)
			    {
				    if (strpos($annotation, '$' . $parameter->getName()) !== false)
				    {
					    $annotation = trim(preg_replace('/ +/', ' ', $annotation));
	                    list($type, $name) = explode(' ', $annotation, 2);

	                    if (strpos($type, '$') === false)
	                    {
		                    $parsedParameters[$parameter->getName()] = $type;
	                    }

					    break;
				    }
			    }
		    }
	    }

	    return $parsedParameters;
    }
}
