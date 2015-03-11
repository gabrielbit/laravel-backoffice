<?php namespace Digbang\L4Backoffice\Commands;

use Digbang\L4Backoffice\Auth\ValueObjects\Permission;
use Digbang\L4Backoffice\Auth\Services\GroupService;
use Digbang\L4Backoffice\Auth\Services\UserService;
use Cartalyst\Sentry\Users\UserNotFoundException;
use Digbang\Security\Permissions\PermissionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
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

	/**
	 * @type PermissionRepository
	 */
	protected $permissionRepository;

	/**
	 * @type Filesystem
	 */
	protected $filesystem;

	/**
	 * @type UserService
	 */
	private $userService;

	/**
	 * @type GroupService
	 */
	private $groupService;

	/**
	 * Create a new command instance.
	 *
	 * @param Filesystem           $filesystem
	 * @param PermissionRepository $permissionRepository
	 * @param UserService          $userService
	 * @param GroupService         $groupService
	 */
	public function __construct(Filesystem $filesystem, PermissionRepository $permissionRepository, UserService $userService, GroupService $groupService)
	{
		parent::__construct();

		$this->filesystem = $filesystem;
		$this->permissionRepository = $permissionRepository;
		$this->userService = $userService;
		$this->groupService = $groupService;
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		if (! $this->option('no-groups'))
		{
			$this->fireGroupCreation($this->groupService);
		}

		if (! $this->option('no-superuser'))
		{
			$this->fireSuperUserCreation($this->userService);
		}

		if (! $this->option('no-configs'))
		{
			$this->fireConfigPublishing();
		}

		if (! $this->option('no-lang-files'))
		{
			$this->fireLangPublishing($this->filesystem);
		}
	}

	protected function copyLanguageFiles(Filesystem $filesystem)
	{
		$myLangPath = realpath(__DIR__ . '/../../../lang');
		$projectLangPath = app_path('lang/packages');

		if (! $filesystem->isDirectory($projectLangPath))
		{
			$filesystem->makeDirectory($projectLangPath, 2775, true);
		}

		foreach (new \FilesystemIterator($myLangPath, \FilesystemIterator::SKIP_DOTS) as $languageDir) /* @type $languageDir \DirectoryIterator */
		{
			$targetDir = $projectLangPath . '/' . $languageDir->getBasename() . '/l4-backoffice';

			$dooEet = true;
			if ($filesystem->exists($targetDir))
			{
				$dooEet = $this->confirm("</question><error>Files already detected at $targetDir.</error>" . PHP_EOL . "<question>Do you want to overwrite them?", false);
			}

			if ($dooEet)
			{
				$this->info(
					'Copying ' . $languageDir->getPathname() . ' to ' .
					$projectLangPath . '/' . $languageDir->getBasename() . '/l4-backoffice'
				);

				$filesystem->copyDirectory($languageDir->getPathname(), $targetDir);
			}
		}
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [
			['no-configs',    'C', InputOption::VALUE_NONE, 'Run without configuration publishing'],
			['no-groups',     'G', InputOption::VALUE_NONE, 'Run without admin group creation'],
			['no-superuser',  'S', InputOption::VALUE_NONE, 'Run without superuser creation'],
			['no-lang-files', 'L', InputOption::VALUE_NONE, 'Run without copying language files']
		];
	}

	private function createSuperuser(UserService $userService)
	{
		$password = $this->secret('Please insert the superuser password: ');

		return $userService->create(
			'admin@digbang.com',
			$password,
			'Admin',
			'Digbang',
			true,
			[],
			[],
			true
		);
	}

	private function fireGroupCreation(GroupService $groupService)
	{
		$groups = $groupService->all();
		if (count($groups) == 0)
		{
			$permissions = new ArrayCollection(array_unique($this->permissionRepository->all()));
			$this->info("Creating Administrators backoffice group...");

			$groupService->create('Administrators', $permissions->toArray());

			foreach ($permissions as $permission)
			{
				/** @type Permission $permission */
				if (preg_match('/\.backoffice\-(users|groups)\./', (string)$permission))
				{
					$permissions->removeElement($permission);
				}
			}

			$this->info("Creating Operators backoffice group...");
			$groupService->create('Operators', $permissions->toArray());
		}
	}

	private function fireSuperUserCreation(UserService $userService)
	{
		$this->info("Creating backoffice superuser...");
		try
		{
			$userService->findByLogin('admin@digbang.com');
			$this->info("User already exists.");
		}
		catch (UserNotFoundException $e)
		{
			$this->createSuperuser($userService);
		}
	}

	private function fireConfigPublishing()
	{
		$this->info("Publishing backoffice and security configuration files...");
		$this->call('config:publish', ['digbang/l4-backoffice']);
		$this->call('config:publish', ['digbang/security']);
	}

	private function fireLangPublishing(Filesystem $files)
	{
		$this->info('Publishing backoffice language files...');
		$this->copyLanguageFiles($files);
	}
}
