<?php namespace Digbang\L4Backoffice\Commands;

use Digbang\Security\Entities\Group;
use Cartalyst\Sentry\Sentry;
use Cartalyst\Sentry\Users\UserNotFoundException;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class InstallCommand extends Command
{
	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'backoffice:install';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Install the backoffice interactively.';

	protected $sentry;

	/**
	 * Create a new command instance.
	 */
	public function __construct(Sentry $sentry)
	{
		parent::__construct();

		$this->sentry = $sentry;
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		// Run the security migrations
		if (! $this->option('no-migrations'))
		{
			$this->info("Installing digbang/security migrations...");
			$this->call('security:migrations');
		}

		if (! $this->option('no-groups'))
		{
			if (Group::count() == 0) {
				$this->info("Creating Administrators backoffice group...");
				Group::create(['name' => 'Administrators']);
			}
		}

		if (! $this->option('no-superuser'))
		{
			$this->info("Creating backoffice superuser...");
			try
			{
				$this->sentry->findUserByLogin('admin@digbang.com');
				$this->info("User already exists.");
			}
			catch (UserNotFoundException $e)
			{
				$this->createSuperuser();
			}
		}

		if (! $this->option('no-configs'))
		{
			$this->info("Publishing backoffice and security configuration files...");
			$this->call('config:publish', ['digbang/l4-backoffice']);
			$this->call('config:publish', ['digbang/security']);
		}
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [
			['no-migrations', 'M', InputOption::VALUE_NONE, 'Run without security migrations'],
			['no-configs',    'C', InputOption::VALUE_NONE, 'Run without configuration publishing'],
			['no-groups',     'G', InputOption::VALUE_NONE, 'Run without admin group creation'],
			['no-superuser',  'S', InputOption::VALUE_NONE, 'Run without superuser creation']
		];
	}

	protected function createSuperuser()
	{
		$password = $this->secret('Please insert the superuser password: ');

		$this->sentry->createUser([
			'email'       => 'admin@digbang.com',
			'password'    => $password,
			'activated'   => true,
			'permissions' => ['superuser' => 1]
		]);
	}
}