<?php namespace Digbang\L4Backoffice\Auth;

use Digbang\L4Backoffice\Backoffice;
use Digbang\L4Backoffice\Repositories\BackofficeRepositoryFactory;
use Digbang\L4Backoffice\Listings\Listing;
use Digbang\L4Backoffice\Exceptions\ValidationException;
use Digbang\L4Backoffice\Support\PermissionParser;
use Digbang\Security\Entities\User;
use Digbang\Security\Entities\Group;
use Digbang\Security\Permissions\Exceptions\PermissionException;
use Digbang\Security\Permissions\PermissionRepository;
use Digbang\Security\Urls\SecureUrl;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Digbang\FontAwesome\Facade as FontAwesome;
use Maatwebsite\Excel\Excel;

class UserController extends Controller
{
	/**
     * @var \Digbang\L4Backoffice\Backoffice
     */
    protected $backoffice;

    /**
     * @var \Digbang\L4Backoffice\Repositories\BackofficeRepository
     */
    protected $usersRepository;

    /**
     * @var \Digbang\L4Backoffice\Repositories\BackofficeRepository
     */
    protected $groupsRepository;

	/**
     * @var \Maatwebsite\Excel\Excel
     */
    protected $excelExporter;

	protected $permissionsRepository;
	protected $permissionParser;
	protected $secureUrl;

    protected $title;
    protected $titlePlural;

	function __construct(Backoffice $backoffice, BackofficeRepositoryFactory $repositoryFactory, Excel $excelExporter, PermissionRepository $permissionRepository, PermissionParser $permissionParser, SecureUrl $secureUrl)
	{
		$this->backoffice = $backoffice;

		$this->usersRepository       = $repositoryFactory->makeForEloquentModel(new User());
		$this->groupsRepository      = $repositoryFactory->makeForEloquentModel(new Group());
		$this->permissionsRepository = $permissionRepository;

		$this->excelExporter = $excelExporter;
		$this->permissionParser = $permissionParser;
		$this->secureUrl = $secureUrl;

		$this->title = \Lang::get('l4-backoffice::auth.user');
		$this->titlePlural = \Lang::get('l4-backoffice::auth.users');
	}

	public function index()
	{
		$list = $this->getListing();

		$this->buildFilters($list);

		$this->buildListActions($list);

		$list->fill($this->getData());

		$breadcrumb = $this->backoffice->breadcrumb([\Lang::get('l4-backoffice::default.home') => 'backoffice.index', $this->titlePlural]);

		return \View::make('l4-backoffice::index', [
			'title'      => $this->titlePlural,
			'list'       => $list,
			'breadcrumb' => $breadcrumb
		]);
	}

	public function create()
	{
		$label = \Lang::get('l4-backoffice::default.new', ['model' => $this->title]);

		$form = $this->buildForm(
			$this->secureUrl->route('backoffice.backoffice-users.store'),
			$label,
			'POST',
			$this->secureUrl->route('backoffice.backoffice-users.index')
		);

		$breadcrumb = $this->backoffice->breadcrumb([
			\Lang::get('l4-backoffice::default.home') => 'backoffice.index',
			$this->titlePlural => 'backoffice.backoffice-users.index',
			$label
		]);

		return \View::make('l4-backoffice::create', [
			'title'      => $this->titlePlural,
			'form'       => $form,
			'breadcrumb' => $breadcrumb
		]);
	}

	public function store()
	{
		$inputData = array_filter(\Input::only([
			'first_name',
			'last_name',
			'email',
            'password',
            'activated'
        ]), function($input){
            return !empty($input) && $input !== false;
        });

		try
		{
			$this->validate($inputData);

			/* @var $user \Digbang\Security\Entities\User */
			$user = $this->usersRepository->create($inputData);

			if ($groups = \Input::get('groups'))
			{
				foreach ($groups as $groupId)
				{
					if ($group = $this->groupsRepository->findById($groupId))
					{
						$user->addGroup($group);
					}
				}
			}

			$user->setAllPermissions(\Input::get('permissions', []));
			$user->save();

			return \Redirect::to($this->secureUrl->route('backoffice.backoffice-users.show', $user->getKey()));
		}
		catch (ValidationException $e)
		{
			return \Redirect::back()->withInput()->withErrors($e->getErrors());
		}
		catch (PermissionException $e)
		{
			return \Redirect::to($this->secureUrl->route('backoffice.index'));
		}
	}

	public function show($id)
	{
		/* @var $user \Digbang\Security\Entities\User */
		$user = $this->usersRepository->findById($id);

		$breadcrumb = $this->backoffice->breadcrumb([
			\Lang::get('l4-backoffice::default.home') => 'backoffice.index',
			$this->titlePlural => 'backoffice.backoffice-users.index',
			\Lang::get(
				'l4-backoffice::auth.user_name',
				['name' => $user->first_name, 'lastname' => $user->last_name]
			)
		]);

		$data = [
			\Lang::get('l4-backoffice::auth.first_name') => $user->first_name,
			\Lang::get('l4-backoffice::auth.last_name') => $user->last_name,
			\Lang::get('l4-backoffice::auth.email') => $user->email,
			\Lang::get('l4-backoffice::auth.permissions') => $this->permissionParser->toViewTable($this->permissionsRepository->all(), $user),
			\Lang::get('l4-backoffice::auth.activated') => \Lang::get('l4-backoffice::default.' . ($user->activated ? 'yes' : 'no')),
			\Lang::get('l4-backoffice::auth.activated_at') => $user->activated_at,
			\Lang::get('l4-backoffice::auth.last_login') => $user->last_login
		];

		// Actions with security concerns
		$actions = $this->backoffice->actions();
		try {
			$actions->link($this->secureUrl->route('backoffice.backoffice-users.edit', $id), FontAwesome::icon('edit') . ' ' . \Lang::get('l4-backoffice::default.edit'), ['class' => 'btn btn-success']);
		} catch (PermissionException $e) { /* Do nothing */ }
		try {
			$actions->link($this->secureUrl->route('backoffice.backoffice-users.index'), \Lang::get('l4-backoffice::default.back'), ['class' => 'btn btn-default']);
		} catch (PermissionException $e) { /* Do nothing */ }

		$topActions = $this->backoffice->actions();

		try {
			$topActions->link($this->secureUrl->route('backoffice.backoffice-users.index'), FontAwesome::icon('arrow-left') . ' ' . \Lang::get('l4-backoffice::default.back'));
		} catch (PermissionException $e) { /* Do nothing */ }

		return \View::make('l4-backoffice::show', [
			'title'      => $this->titlePlural,
			'breadcrumb' => $breadcrumb,
			'label'      => \Lang::get(
					'l4-backoffice::auth.user_name',
					['name' => $user->first_name, 'lastname' => $user->last_name]
				),
			'data'       => $data,
			'actions'    => $actions,
			'topActions' => $topActions
		]);
	}

	public function edit($id)
	{
		/* @var $user \Digbang\Security\Entities\User */
		$user = $this->usersRepository->findById($id);

		$label = \Lang::get('l4-backoffice::default.edit');

		$form = $this->buildForm(
			$this->secureUrl->route('backoffice.backoffice-users.update', $id),
			$label,
			'PUT',
			$this->secureUrl->route('backoffice.backoffice-users.show', $id)
		);

		$permissions = $user->getAllPermissions();

		$form->fill([
			'first_name'    => $user->first_name,
			'last_name'     => $user->last_name,
			'email'         => $user->email,
			'permissions[]' => array_keys(array_filter($permissions, function($isAllowed){ return $isAllowed == 1; })),
			'activated'     => $user->activated,
			'groups[]'      => $user->groups->modelKeys()
		]);

		$breadcrumb = $this->backoffice->breadcrumb([
			\Lang::get('l4-backoffice::default.home') => 'backoffice.index',
			$this->titlePlural => 'backoffice.backoffice-users.index',
			\Lang::get(
					'l4-backoffice::auth.user_name',
					['name' => $user->first_name, 'lastname' => $user->last_name]
				) => ['backoffice.backoffice-users.show', $id],
			\Lang::get('l4-backoffice::default.edit')
		]);

		return \View::make('l4-backoffice::edit', [
			'title'      => \Lang::get('l4-backoffice::default.edit_model', ['model' => $this->title]),
			'form'       => $form,
			'breadcrumb' => $breadcrumb
		]);
	}

	public function update($id)
	{
		/* @var $user \Digbang\Security\Entities\User */
		$user = $this->usersRepository->findById($id);

		// Get the input
		$inputData = array_filter(\Input::only([
			'first_name',
			'last_name',
			'email',
            'password',
			'permissions',
        ]), function($value){
	         return !empty($value) && $value !== false;
	    });

		$inputData['activated'] = \Input::get('activated', false);

		try
		{
			$this->validate($inputData, true);
			$user->fill($inputData);

			$user->groups()->sync(\Input::get('groups', []));

			$user->setAllPermissions(\Input::get('permissions', []));

			$user->save();

			return \Redirect::to($this->secureUrl->route('backoffice.backoffice-users.show', [$user->getKey()]));
		}
		catch (ValidationException $e)
		{
			return \Redirect::back()->withInput()->withErrors($e->getErrors());
		}
		catch (PermissionException $e)
		{
			return \Redirect::to($this->secureUrl->route('backoffice.index'));
		}

	}

	public function destroy($id)
	{
		try
		{
			// Try to destroy the entity
			$this->usersRepository->destroy($id);

			// Redirect to the listing
			return \Redirect::to($this->secureUrl->route('backoffice.backoffice-users.index'))->withSuccess(
				\Lang::get('l4-backoffice::default.delete_msg', ['model' => $this->title, 'id' => $id])
			);
		}
		catch (ValidationException $e)
		{
			return \Redirect::back()->withDanger(implode('<br/>', $e->getErrors()));
		}
		catch(PermissionException $e)
		{
			return \Redirect::to($this->secureUrl->route('backoffice.index'))->withDanger(
				\Lang::get('l4-backoffice::auth.permission_error')
			);
		}
	}

	public function export()
	{
		$list = $this->getListing();

		$list->fill($this->getData(null));

		$columns = $list->columns()->hide([])->sortable([]);
		$rows = $list->rows();

		$this->excelExporter->create(\Str::slug($this->titlePlural), function($excel) use ($columns, $rows) {
			$excel->sheet($this->titlePlural, function($sheet) use ($columns, $rows) {
				$sheet->loadView('l4-backoffice::lists.list', [
					'bulkActions' => [],
					'rowActions' => [],
					'columns' => $columns,
					'items' => $rows
				]);
			});
		})->download('xls');
	}

	protected function buildForm($target, $label, $method = 'POST', $cancelAction = '', $options = [])
	{
		$form = $this->backoffice->form($target, $label, $method, $cancelAction, $options);

		$inputs = $form->inputs();

		$inputs->text('first_name',    \Lang::get('l4-backoffice::auth.first_name'));
		$inputs->text('last_name',     \Lang::get('l4-backoffice::auth.last_name'));
		$inputs->text('email',         \Lang::get('l4-backoffice::auth.email'));
		$inputs->password('password',  \Lang::get('l4-backoffice::auth.password'));
		$inputs->checkbox('activated', \Lang::get('l4-backoffice::auth.activated'));

		$groups = $this->groupsRepository->all();
		$inputs->dropdown(
			'groups',
			\Lang::get('l4-backoffice::auth.groups'),
			$groups->lists('name', 'id'),
			[
				'placeholder' => \Lang::get('l4-backoffice::auth.groups'),
				'multiple' => 'multiple',
				'class' => 'user-groups form-control',
				'data-permissions' => json_encode($groups->reduce(function($permissions, $group){
					$permissions[$group->id] = array_keys($group->permissions);
					return $permissions;
				}, []))
			]
		);

		$permissions = $this->permissionsRepository->all();
		$inputs->dropdown(
			'permissions',
			\Lang::get('l4-backoffice::auth.permissions'),
			$this->permissionParser->toDropdownArray($permissions),
			[
				'multiple' => 'multiple',
				'class' => 'multiselect'
			]
		);

		return $form;
	}

	/**
	 * @param $list
	 */
	protected function buildFilters(Listing $list)
	{
		$filters = $list->filters();

		$filters->string('email',      \Lang::get('l4-backoffice::auth.email'),      ['class' => 'form-control']);
		$filters->string('first_name', \Lang::get('l4-backoffice::auth.first_name'), ['class' => 'form-control']);
		$filters->string('last_name',  \Lang::get('l4-backoffice::auth.last_name'),  ['class' => 'form-control']);
		$filters->boolean('activated', \Lang::get('l4-backoffice::auth.activated'),  ['class' => 'form-control']);
	}

	/**
	 * @return mixed
	 */
	protected function getListing()
	{
		$listing = $this->backoffice->listing([
			'email'      => \Lang::get('l4-backoffice::auth.email'),
			'name'       => \Lang::get('l4-backoffice::auth.name'),
			'activated'  => \Lang::get('l4-backoffice::auth.activated'),
			'last_login' => \Lang::get('l4-backoffice::auth.last_login'),
			'id',
			'first_name',
			'last_name',
		]);

		$columns = $listing->columns();
		$columns
			->hide([
				'id',
				'first_name',
				'last_name',
			])
			->sortable([
				'email',
				'activated',
				'last_login'
		]);

		$columns->setAccessor('name', function($row){
			return \Lang::get('l4-backoffice::auth.user_name',
				[
					'name'     => $row['first_name'],
					'lastname' => $row['last_name']
				]
			);
		});

		return $listing;
	}

	protected function buildListActions(Listing $list)
	{
		$actions = $this->backoffice->actions();

		try {
			$actions->link($this->secureUrl->route('backoffice.backoffice-users.create'), FontAwesome::icon('plus') . \Lang::get('l4-backoffice::default.new', ['model' => $this->title]), ['class' => 'btn btn-primary']);
		} catch (PermissionException $e) { /* Do nothing */}
		try {
			$actions->link($this->secureUrl->route('backoffice.backoffice-users.export', \Input::all()), FontAwesome::icon('file-excel-o') . ' ' . \Lang::get('l4-backoffice::default.export'), ['class' => 'btn btn-success']);
		} catch (PermissionException $e) { /* Do nothing */}

		$list->setActions($actions);

		$rowActions = $this->backoffice->actions();

		// View icon
		$rowActions->link(function(Collection $row) {
			try {
				return $this->secureUrl->route('backoffice.backoffice-users.show', $row['id']);
			} catch (PermissionException $e) { return false; }
		}, FontAwesome::icon('eye'), ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => \Lang::get('l4-backoffice::default.show')]);

		// Edit icon
		$rowActions->link(function(Collection $row){
			try {
				return $this->secureUrl->route('backoffice.backoffice-users.edit', $row['id']);
			} catch (PermissionException $e) { return false; }
		}, FontAwesome::icon('edit'), ['class' => 'text-success', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => \Lang::get('l4-backoffice::default.edit')]);

		// Delete icon
		$rowActions->form(
			function(Collection $row){
				try {
				return $this->secureUrl->route('backoffice.backoffice-users.destroy', $row['id']);
				} catch (PermissionException $e) { return false; }
			},
			FontAwesome::icon('times'),
			'DELETE',
			[
				'class'          => 'text-danger',
				'data-toggle'    => 'tooltip',
				'data-placement' => 'top',
				'data-confirm'   => \Lang::get('l4-backoffice::default.delete-confirm'),
				'title'          => \Lang::get('l4-backoffice::default.delete')
			],
			true
		);

		$rowActions->form(
			function(Collection $row){
				try {
				return $this->secureUrl->route('backoffice.backoffice-users.reset-password', $row['id']);
				} catch (PermissionException $e) { return false; }
			},
			FontAwesome::icon('unlock-alt'),
			'POST',
			[
				'class'          => 'text-warning',
				'data-toggle'    => 'tooltip',
				'data-placement' => 'top',
				'data-confirm'   => \Lang::get('l4-backoffice::auth.reset-password.confirm'),
				'title'          => \Lang::get('l4-backoffice::auth.reset-password.title')
			],
			true
		);

		$rowActions->form(
			function(Collection $row){
				if ($row['activated']) return false;
				try {
					return $this->secureUrl->route('backoffice.backoffice-users.resend-activation', $row['id']);
				} catch (PermissionException $e) { return false; }
			},
			FontAwesome::icon('reply-all'),
			'POST',
			[
				'class'          => 'text-primary',
				'data-toggle'    => 'tooltip',
				'data-placement' => 'top',
				'title'          => \Lang::get('l4-backoffice::auth.activation.title')
			],
			true
		);

		$list->setRowActions($rowActions);
	}

	/**
	 * @param int $limit
	 * @return array
	 */
	protected function getData($limit = 10)
	{
		return $this->usersRepository->search(
            \Input::except(['page', 'sort_by', 'sort_sense']),
            \Input::get('sort_by'),
            \Input::get('sort_sense'),
            $limit,
            \Input::get('page', 1)
        );
	}

    protected function validate($inputData, $update = false)
    {
        $validationRules = [
            'email' => 'required',
            'password' => ($update ? 'sometimes|' : '') . 'required|min:3'
        ];

        $validationMsgs = [
            'email.required'    => \Lang::get('l4-backoffice::auth.validation.user.email-required'),
            'password.required' => \Lang::get('l4-backoffice::auth.validation.user.password-required')
        ];

	    /* @var $validator \Illuminate\Validation\Validator */
        $validator = \Validator::make($inputData, $validationRules, $validationMsgs);

        if ($validator->fails())
        {
            throw new ValidationException($validator->errors());
        }
    }
}
