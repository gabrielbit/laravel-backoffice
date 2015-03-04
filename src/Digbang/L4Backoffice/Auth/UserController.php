<?php namespace Digbang\L4Backoffice\Auth;

use Digbang\L4Backoffice\Auth\Contracts\Group;
use Digbang\L4Backoffice\Auth\Services\UserService;
use Digbang\L4Backoffice\Backoffice;
use Digbang\L4Backoffice\Listings\Listing;
use Digbang\L4Backoffice\Exceptions\ValidationException;
use Digbang\L4Backoffice\Support\PermissionParser;
use Digbang\Security\Permissions\Exceptions\PermissionException;
use Digbang\Security\Permissions\PermissionRepository;
use Digbang\Security\Urls\SecureUrl;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Digbang\FontAwesome\Facade as FontAwesome;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
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

	/**
	 * @type \Digbang\Security\Permissions\PermissionRepository
	 */
	protected $permissionsRepository;

	/**
	 * @type \Digbang\L4Backoffice\Support\PermissionParser
	 */
	protected $permissionParser;

	/**
	 * @type \Digbang\Security\Urls\SecureUrl
	 */
	protected $secureUrl;

	/**
	 * @type \Digbang\L4Backoffice\Auth\Services\UserService
	 */
	private $userService;

	/**
	 * @type \Digbang\L4Backoffice\Auth\Services\GroupService
	 */
	private $groupService;

	/**
	 * @type string
	 */
    protected $title;
	/**
	 * @type string
	 */
    protected $titlePlural;

	function __construct(Backoffice $backoffice, Excel $excelExporter, PermissionRepository $permissionRepository, PermissionParser $permissionParser, SecureUrl $secureUrl, UserService $userService, GroupService $groupService)
	{
		$this->backoffice            = $backoffice;
		$this->permissionsRepository = $permissionRepository;
		$this->excelExporter         = $excelExporter;
		$this->permissionParser      = $permissionParser;
		$this->secureUrl             = $secureUrl;
		$this->userService           = $userService;

		$this->title       = trans('l4-backoffice::auth.user');
		$this->titlePlural = trans('l4-backoffice::auth.users');
	}

	public function index()
	{
		$list = $this->getListing();

		$this->buildFilters($list);

		$this->buildListActions($list);

		$list->fill($this->getData());

		$breadcrumb = $this->backoffice->breadcrumb([
			trans('l4-backoffice::default.home') => 'backoffice.index',
			$this->titlePlural
		]);

		return View::make('l4-backoffice::index', [
			'title'      => $this->titlePlural,
			'list'       => $list,
			'breadcrumb' => $breadcrumb
		]);
	}

	public function create()
	{
		$label = trans('l4-backoffice::default.new', ['model' => $this->title]);

		$form = $this->buildForm(
			$this->secureUrl->route('backoffice.backoffice-users.store'),
			$label,
			'POST',
			$this->secureUrl->route('backoffice.backoffice-users.index')
		);

		$breadcrumb = $this->backoffice->breadcrumb([
			trans('l4-backoffice::default.home') => 'backoffice.index',
			$this->titlePlural => 'backoffice.backoffice-users.index',
			$label
		]);

		return View::make('l4-backoffice::create', [
			'title'      => $this->titlePlural,
			'form'       => $form,
			'breadcrumb' => $breadcrumb
		]);
	}

	public function store()
	{
		$inputData = array_filter(Input::only([
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

			$user = $this->userService->create(
				$inputData['email'],
				$inputData['password'],
				array_get($inputData, 'first_name'),
				array_get($inputData, 'last_name'),
				array_get($inputData, 'activated')
			);

			if ($groups = Input::get('groups'))
			{
				foreach ($groups as $groupId)
				{
					if ($group = $this->groupsRepository->findById($groupId))
					{
						$user->addGroup($group);
					}
				}
			}

			$user->setAllPermissions(Input::get('permissions', []));
			$user->save();

			return Redirect::to($this->secureUrl->route('backoffice.backoffice-users.show', $user->getId()));
		}
		catch (ValidationException $e)
		{
			return Redirect::back()->withInput()->withErrors($e->getErrors());
		}
		catch (PermissionException $e)
		{
			return Redirect::to($this->secureUrl->route('backoffice.index'));
		}
	}

	public function show($id)
	{
		/* @var $user \Digbang\L4Backoffice\Auth\Contracts\User */
		$user = $this->userService->find($id);

		$breadcrumb = $this->backoffice->breadcrumb([
			trans('l4-backoffice::default.home') => 'backoffice.index',
			$this->titlePlural => 'backoffice.backoffice-users.index',
			trans(
				'l4-backoffice::auth.user_name',
				['name' => $user->getFirstName(), 'lastname' => $user->getLastName()]
			)
		]);

		$data = [
			trans('l4-backoffice::auth.first_name')   => $user->getFirstName(),
			trans('l4-backoffice::auth.last_name')    => $user->getLastName(),
			trans('l4-backoffice::auth.email')        => $user->getEmail(),
			trans('l4-backoffice::auth.permissions')  => $this->permissionParser->toViewTable($this->permissionsRepository->all(), $user),
			trans('l4-backoffice::auth.activated')    => trans('l4-backoffice::default.' . ($user->isActivated() ? 'yes' : 'no')),
			trans('l4-backoffice::auth.activated_at') => $user->getActivatedAt(),
			trans('l4-backoffice::auth.last_login')   => $user->getLastLogin()
		];

		// Actions with security concerns
		$actions = $this->backoffice->actions();
		try {
			$actions->link($this->secureUrl->route('backoffice.backoffice-users.edit', $id), FontAwesome::icon('edit') . ' ' . trans('l4-backoffice::default.edit'), ['class' => 'btn btn-success']);
		} catch (PermissionException $e) { /* Do nothing */ }
		try {
			$actions->link($this->secureUrl->route('backoffice.backoffice-users.index'), trans('l4-backoffice::default.back'), ['class' => 'btn btn-default']);
		} catch (PermissionException $e) { /* Do nothing */ }

		$topActions = $this->backoffice->actions();

		try {
			$topActions->link($this->secureUrl->route('backoffice.backoffice-users.index'), FontAwesome::icon('arrow-left') . ' ' . trans('l4-backoffice::default.back'));
		} catch (PermissionException $e) { /* Do nothing */ }

		return View::make('l4-backoffice::show', [
			'title'      => $this->titlePlural,
			'breadcrumb' => $breadcrumb,
			'label'      => trans('l4-backoffice::auth.user_name', [
				'name'     => $user->getFirstName(),
				'lastname' => $user->getLastName()
			]),
			'data'       => $data,
			'actions'    => $actions,
			'topActions' => $topActions
		]);
	}

	public function edit($id)
	{
		/* @var $user \Digbang\L4Backoffice\Auth\Contracts\User */
		$user = $this->userService->find($id);

		$label = trans('l4-backoffice::default.edit');

		$form = $this->buildForm(
			$this->secureUrl->route('backoffice.backoffice-users.update', $id),
			$label,
			'PUT',
			$this->secureUrl->route('backoffice.backoffice-users.show', $id)
		);

		$permissions = $user->getAllPermissions();

		$form->fill([
			'first_name'    => $user->getFirstName(),
			'last_name'     => $user->getLastName(),
			'email'         => $user->getEmail(),
			'permissions[]' => array_keys(array_filter($permissions, function($isAllowed){ return $isAllowed == 1; })),
			'activated'     => $user->isActivated(),
			'groups[]'      => array_map(function(Group $group){
				return $group->getId();
			}, $user->getGroups())
		]);

		$breadcrumb = $this->backoffice->breadcrumb([
			trans('l4-backoffice::default.home') => 'backoffice.index',
			$this->titlePlural => 'backoffice.backoffice-users.index',
			trans('l4-backoffice::auth.user_name', [
					'name' => $user->getFirstName(),
					'lastname' => $user->getLastName()
			]) => ['backoffice.backoffice-users.show', $id],
			trans('l4-backoffice::default.edit')
		]);

		return View::make('l4-backoffice::edit', [
			'title'      => trans('l4-backoffice::default.edit_model', ['model' => $this->title]),
			'form'       => $form,
			'breadcrumb' => $breadcrumb
		]);
	}

	public function update($id)
	{
		/* @var $user \Digbang\L4Backoffice\Auth\Contracts\User */
		$user = $this->userService->find($id);

		// Get the input
		$inputData = array_filter(Input::only([
			'first_name',
			'last_name',
			'email',
            'password',
			'permissions',
        ]), function($value){
	         return !empty($value) && $value !== false;
	    });

		$inputData['activated'] = Input::get('activated', false);

		try
		{
			$this->validate($inputData, true);

			$this->userService->edit(
				$user,
				array_get($inputData, 'first_name'),
				array_get($inputData, 'last_name'),
				array_get($inputData, 'email'),
				array_get($inputData, 'password'),
				Input::get('groups', []),
				Input::get('permissions', [])
			);

			return Redirect::to($this->secureUrl->route('backoffice.backoffice-users.show', [$user->getId()]));
		}
		catch (ValidationException $e)
		{
			return Redirect::back()->withInput()->withErrors($e->getErrors());
		}
		catch (PermissionException $e)
		{
			return Redirect::to($this->secureUrl->route('backoffice.index'));
		}

	}

	public function destroy($id)
	{
		try
		{
			// Try to destroy the entity
			$this->userService->delete($id);

			// Redirect to the listing
			return Redirect::to($this->secureUrl->route('backoffice.backoffice-users.index'))->withSuccess(
				trans('l4-backoffice::default.delete_msg', ['model' => $this->title, 'id' => $id])
			);
		}
		catch (ValidationException $e)
		{
			return Redirect::back()->withDanger(implode('<br/>', $e->getErrors()));
		}
		catch(PermissionException $e)
		{
			return Redirect::to($this->secureUrl->route('backoffice.index'))->withDanger(
				trans('l4-backoffice::auth.permission_error')
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

		$inputs->text('first_name',    trans('l4-backoffice::auth.first_name'));
		$inputs->text('last_name',     trans('l4-backoffice::auth.last_name'));
		$inputs->text('email',         trans('l4-backoffice::auth.email'));
		$inputs->password('password',  trans('l4-backoffice::auth.password'));
		$inputs->checkbox('activated', trans('l4-backoffice::auth.activated'));

		$groups = $this->groupService->all();

		$options = [];
		foreach ($groups as $group)
		{
			/** @type Group $group */
			$options[$group->getId()] = $group->getName();
		}

		$inputs->dropdown(
			'groups',
			trans('l4-backoffice::auth.groups'),
			$options,
			[
				'placeholder' => trans('l4-backoffice::auth.groups'),
				'multiple' => 'multiple',
				'class' => 'user-groups form-control',
				'data-permissions' => json_encode(array_reduce($groups, function($permissions, $group){
					$permissions[$group->id] = array_keys($group->permissions);
					return $permissions;
				}, []))
			]
		);

		$permissions = $this->permissionsRepository->all();
		$inputs->dropdown(
			'permissions',
			trans('l4-backoffice::auth.permissions'),
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

		$filters->string('email',      trans('l4-backoffice::auth.email'),      ['class' => 'form-control']);
		$filters->string('first_name', trans('l4-backoffice::auth.first_name'), ['class' => 'form-control']);
		$filters->string('last_name',  trans('l4-backoffice::auth.last_name'),  ['class' => 'form-control']);
		$filters->boolean('activated', trans('l4-backoffice::auth.activated'),  ['class' => 'form-control']);
	}

	/**
	 * @return mixed
	 */
	protected function getListing()
	{
		$listing = $this->backoffice->listing([
			'email'      => trans('l4-backoffice::auth.email'),
			'name'       => trans('l4-backoffice::auth.name'),
			'activated'  => trans('l4-backoffice::auth.activated'),
			'last_login' => trans('l4-backoffice::auth.last_login'),
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
			return trans('l4-backoffice::auth.user_name',
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
			$actions->link($this->secureUrl->route('backoffice.backoffice-users.create'), FontAwesome::icon('plus') . trans('l4-backoffice::default.new', ['model' => $this->title]), ['class' => 'btn btn-primary']);
		} catch (PermissionException $e) { /* Do nothing */}
		try {
			$actions->link($this->secureUrl->route('backoffice.backoffice-users.export', Input::all()), FontAwesome::icon('file-excel-o') . ' ' . trans('l4-backoffice::default.export'), ['class' => 'btn btn-success']);
		} catch (PermissionException $e) { /* Do nothing */}

		$list->setActions($actions);

		$rowActions = $this->backoffice->actions();

		// View icon
		$rowActions->link(function(Collection $row) {
			try {
				return $this->secureUrl->route('backoffice.backoffice-users.show', $row['id']);
			} catch (PermissionException $e) { return false; }
		}, FontAwesome::icon('eye'), ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => trans('l4-backoffice::default.show')]);

		// Edit icon
		$rowActions->link(function(Collection $row){
			try {
				return $this->secureUrl->route('backoffice.backoffice-users.edit', $row['id']);
			} catch (PermissionException $e) { return false; }
		}, FontAwesome::icon('edit'), ['class' => 'text-success', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => trans('l4-backoffice::default.edit')]);

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
				'data-confirm'   => trans('l4-backoffice::default.delete-confirm'),
				'title'          => trans('l4-backoffice::default.delete')
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
				'data-confirm'   => trans('l4-backoffice::auth.reset-password.confirm'),
				'title'          => trans('l4-backoffice::auth.reset-password.title')
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
				'title'          => trans('l4-backoffice::auth.activation.title')
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
            Input::except(['page', 'sort_by', 'sort_sense']),
            Input::get('sort_by'),
            Input::get('sort_sense'),
            $limit,
            Input::get('page', 1)
        );
	}

    protected function validate($inputData, $update = false)
    {
        $validationRules = [
            'email' => 'required',
            'password' => ($update ? 'sometimes|' : '') . 'required|min:3'
        ];

        $validationMsgs = [
            'email.required'    => trans('l4-backoffice::auth.validation.user.email-required'),
            'password.required' => trans('l4-backoffice::auth.validation.user.password-required')
        ];

	    /* @var $validator \Illuminate\Validation\Validator */
        $validator = \Validator::make($inputData, $validationRules, $validationMsgs);

        if ($validator->fails())
        {
            throw new ValidationException($validator->errors());
        }
    }
}
