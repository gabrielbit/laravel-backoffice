<?php namespace Digbang\L4Backoffice\Commands;

use Digbang\L4Backoffice\Generator\Services\ControllerGenerator;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class AuthGenerationCommand extends Command
{
	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'backoffice:auth';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generate the needed Controllers to administrate users, groups and permissions.';

	protected $controllerGenerator;

	public function __construct(ControllerGenerator $controllerGenerator)
	{
		$this->controllerGenerator = $controllerGenerator;

		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$backofficeNamespace = $this->getBackofficeNamespace();
		$entityNamespace = $this->getEntityNamespace();

		$controllersDirPath =
			app_path() . DIRECTORY_SEPARATOR .
			str_replace('\\', DIRECTORY_SEPARATOR, $backofficeNamespace);

		$templatePath = realpath(
			dirname(__DIR__) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR
		);

		foreach (['users', 'groups'] as $tableName)
		{
			$this->controllerGenerator->generate(
				$tableName,
				$templatePath . "{$tableName}controller.mustache",
				$controllersDirPath,
				$backofficeNamespace,
				$entityNamespace
			);
		}

	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array();
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [
			['controllerNamespace', 'cn', InputOption::VALUE_OPTIONAL, 'The controllers\' namespace.', \Config::get('l4-backoffice::gen.backoffice.namespace')],
			['entitiesNamespace',   'en', InputOption::VALUE_OPTIONAL, 'The entities namespace.',      \Config::get('l4-backoffice::gen.entities.namespace')]
		];
	}

	protected function getBackofficeNamespace()
	{
		if (! $backofficeNamespace = $this->option('controllerNamespace'))
		{
			$backofficeNamespace = $this->ask("Please type in your backoffice controllers' full namespace: ");
		}

		return $backofficeNamespace;
	}

	protected function getEntityNamespace()
	{
		if (! $entityNamespace = $this->option('entitiesNamespace'))
		{
			$entityNamespace = $this->ask("Please type in your entities full namespace: ");
		}

		return $entityNamespace;
	}
}
