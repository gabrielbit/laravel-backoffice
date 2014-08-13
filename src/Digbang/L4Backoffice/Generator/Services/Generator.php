<?php namespace Digbang\L4Backoffice\Generator\Services;

use Digbang\L4Backoffice\Generator\Model\ControllerInput;
use Illuminate\Filesystem\Filesystem;

/**
 * Class Generator
 * @package Digbang\L4Backoffice\Generator\Services
 */
class Generator
{
	protected $mustache;
	protected $filesystem;

	function __construct(\Mustache_Engine $mustache, Filesystem $filesystem)
	{
		$this->mustache = $mustache;
		$this->filesystem = $filesystem;
	}

	public function make($templatePath, $destinationPath, ControllerInput $controllerInput)
	{
		$template = $this->filesystem->get($templatePath);

		$this->filesystem->put(
			$destinationPath,
			$this->mustache->render($template, $controllerInput)
		);
	}
} 