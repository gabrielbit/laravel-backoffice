<?php namespace Digbang\L4Backoffice\Auth\Controllers;

use Cartalyst\Sentry\Users\UserNotFoundException;
use Digbang\Doctrine\Tools\PaginatorFactory;
use Digbang\FontAwesome\Facade as FontAwesome;
use Digbang\L4Backoffice\Auth\Routes\AuthRouteBinder;
use Digbang\L4Backoffice\Auth\Routes\UsersRouteBinder;
use Digbang\L4Backoffice\Backoffice;
use Digbang\L4Backoffice\Exceptions\ValidationException;
use Digbang\L4Backoffice\Listings\Listing;
use Digbang\L4Backoffice\Support\PermissionParser;
use Digbang\L4Backoffice\Urls\PersistentUrl;
use Digbang\Security\Auth\Emailer;
use Digbang\Security\Contracts\Group;
use Digbang\Security\Permissions\Exceptions\PermissionException;
use Digbang\Security\Permissions\PermissionRepository;
use Digbang\Security\Services\UserService;
use Digbang\Security\Services\GroupService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Excel;
use Digbang\Security\Contracts\User;

class UserController extends Controller
{
	/**
     * @var \Digbang\L4Backoffice\Backoffice
     */
    protected $backoffice;

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
	 * @type PersistentUrl
	 */
	protected $persistentUrl;

	/**
	 * @type \Digbang\Security\Services\UserService
	 */
	protected $userService;

	/**
	 * @type \Digbang\Security\Services\GroupService
	 */
	protected $groupService;

	/**
	 * @type string
	 */
    protected $title;

	/**
	 * @type string
	 */
    protected $titlePlural;

	/**
	 * @type Request
	 */
	protected $request;

	/**
	 * @type Emailer
	 */
	private $emailer;

	/**
	 * @type PaginatorFactory
	 */
	private $paginatorFactory;

	/**
	 * @param Backoffice           $backoffice
	 * @param Excel                $excelExporter
	 * @param PermissionRepository $permissionRepository
	 * @param PermissionParser     $permissionParser
	 * @param PersistentUrl        $persistentUrl
	 * @param UserService          $userService
	 * @param GroupService         $groupService
	 * @param Emailer              $emailer
	 * @param Request              $request
	 * @param PaginatorFactory     $paginatorFactory
	 */
	public function __construct(Backoffice $backoffice, Excel $excelExporter, PermissionRepository $permissionRepository, PermissionParser $permissionParser, PersistentUrl $persistentUrl, UserService $userService, GroupService $groupService, Emailer $emailer, Request $request, PaginatorFactory $paginatorFactory)
	{
		$this->backoffice            = $backoffice;
		$this->permissionsRepository = $permissionRepository;
		$this->excelExporter         = $excelExporter;
		$this->permissionParser      = $permissionParser;
		$this->persistentUrl             = $persistentUrl;
		$this->userService           = $userService;
		$this->groupService          = $groupService;
		$this->request               = $request;
		$this->emailer               = $emailer;

		$this->title       = trans('backoffice::auth.user');
		$this->titlePlural = trans('backoffice::auth.users');
		$this->paginatorFactory = $paginatorFactory;
	}

	public function index()
	{
		$list = $this->getListing();

		$this->buildFilters($list);

		$this->buildListActions($list);

		$list->fill($this->getData());

		$breadcrumb = $this->backoffice->breadcrumb([
			trans('backoffice::default.home') => 'backoffice.index',
			$this->titlePlural
		]);

		return View::make('backoffice::index', [
			'title'      => $this->titlePlural,
			'list'       => $list,
			'breadcrumb' => $breadcrumb
		]);
	}

	public function create()
	{
		$label = trans('backoffice::default.new', ['model' => $this->title]);

		$form = $this->buildForm(
			$this->persistentUrl->route(UsersRouteBinder::STORE),
			$label,
			'POST',
			$this->persistentUrl->route(UsersRouteBinder::INDEX)
		);

		$breadcrumb = $this->backoffice->breadcrumb([
			trans('backoffice::default.home') => 'backoffice.index',
			$this->titlePlural                   => UsersRouteBinder::INDEX,
			$label
		]);

		return View::make('backoffice::create', [
			'title'      => $this->titlePlural,
			'form'       => $form,
			'breadcrumb' => $breadcrumb
		]);
	}

	public function store()
	{
		$input = $this->request->only([
			'first_name',
			'last_name',
			'email',
            'password',
            'activated'
        ]);

		try
		{
			$this->validate($input);

			$user = $this->userService->create(
				$input['email'],
				$input['password'],
				array_get($input, 'first_name'),
				array_get($input, 'last_name'),
				array_get($input, 'activated'),
				$this->request->get('groups', []),
				$this->request->get('permissions', [])
			);

			if (!$user->isActivated())
			{
				$this->emailer->sendActivation(
					$user,
					route(AuthRouteBinder::ACTIVATE, [$user->getActivationCode()])
				);
			}

			return Redirect::to($this->persistentUrl->route(UsersRouteBinder::SHOW, $user->getId()));
		}
		catch (ValidationException $e)
		{
			return Redirect::back()->withInput()->withErrors($e->getErrors());
		}
		catch (PermissionException $e)
		{
			return Redirect::to($this->persistentUrl->route('backoffice.index'));
		}
	}

	public function show($id)
	{
		/* @var $user \Digbang\Security\Contracts\User */
		$user = $this->userService->find($id);

		$breadcrumb = $this->backoffice->breadcrumb([
			trans('backoffice::default.home') => 'backoffice.index',
			$this->titlePlural                   => UsersRouteBinder::INDEX,
			trans(
				'backoffice::auth.user_name',
				['name' => $user->getFirstName(), 'lastname' => $user->getLastName()]
			)
		]);

		$data = [
			trans('backoffice::auth.first_name')   => $user->getFirstName(),
			trans('backoffice::auth.last_name')    => $user->getLastName(),
			trans('backoffice::auth.email')        => $user->getEmail(),
			trans('backoffice::auth.permissions')  => $this->permissionParser->toViewTable($this->permissionsRepository->all(), $user),
			trans('backoffice::auth.activated')    => trans('backoffice::default.' . ($user->isActivated() ? 'yes' : 'no')),
			trans('backoffice::auth.activated_at') => $user->getActivatedAt() ? $user->getActivatedAt()->format(trans('backoffice::default.datetime_format')) : '-',
			trans('backoffice::auth.last_login')   => $user->getLastLogin() ? $user->getLastLogin()->format(trans('backoffice::default.datetime_format')) : '-',
			trans('backoffice::auth.groups')       => implode(' ', array_map(function(Group $group){
				return $group->getName();
			}, $user->getGroups()))
		];

		// Actions with security concerns
		$actions = $this->backoffice->actions();
		try {
			$actions->link($this->persistentUrl->route(UsersRouteBinder::EDIT, $id), FontAwesome::icon('edit') . ' ' . trans('backoffice::default.edit'), ['class' => 'btn btn-success']);
		} catch (PermissionException $e) { /* Do nothing */ }
		try {
			$actions->link($this->persistentUrl->route(UsersRouteBinder::INDEX), trans('backoffice::default.back'), ['class' => 'btn btn-default']);
		} catch (PermissionException $e) { /* Do nothing */ }

		$topActions = $this->backoffice->actions();

		try {
			$topActions->link($this->persistentUrl->route(UsersRouteBinder::INDEX), FontAwesome::icon('arrow-left') . ' ' . trans('backoffice::default.back'));
		} catch (PermissionException $e) { /* Do nothing */ }

		return View::make('backoffice::show', [
			'title'      => $this->titlePlural,
			'breadcrumb' => $breadcrumb,
			'label'      => trans('backoffice::auth.user_name', [
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
		/* @var $user \Digbang\Security\Contracts\User */
		$user = $this->userService->find($id);

		$form = $this->buildForm(
			$this->persistentUrl->route(UsersRouteBinder::UPDATE, $id),
			trans('backoffice::default.edit') . ' ' . trans('backoffice::auth.user_name', ['name' => $user->getFirstName(),'lastname' => $user->getLastName()]),
			'PUT',
			$this->persistentUrl->route(UsersRouteBinder::SHOW, $id)
		);

		$permissions = array_map(function($permission){
			return (string) $permission;
		}, $user->getMergedPermissions()->toArray());

		$form->fill([
			'first_name'    => $user->getFirstName(),
			'last_name'     => $user->getLastName(),
			'email'         => $user->getEmail(),
			'permissions[]' => $permissions,
			'activated'     => $user->isActivated(),
			'groups[]'      => array_map(function(Group $group){
				return $group->getId();
			}, $user->getGroups())
		]);

		$breadcrumb = $this->backoffice->breadcrumb([
			trans('backoffice::default.home') => 'backoffice.index',
			$this->titlePlural                   => UsersRouteBinder::INDEX,
			trans('backoffice::auth.user_name', [
					'name' => $user->getFirstName(),
					'lastname' => $user->getLastName()
			]) => [UsersRouteBinder::SHOW, $id],
			trans('backoffice::default.edit')
		]);

		return View::make('backoffice::edit', [
			'title'      => $this->titlePlural,
			'form'       => $form,
			'breadcrumb' => $breadcrumb
		]);
	}

	public function update($id)
	{
		/* @var $user \Digbang\Security\Contracts\User */
		$user = $this->userService->find($id);

		// Get the input
		$inputData = $this->request->only([
			'first_name',
			'last_name',
			'email',
            'password',
			'permissions',
        ]);

		if (empty($inputData['password']))
		{
			unset($inputData['password']);
		}

		try
		{
			$this->validate($inputData, $user);

			$this->userService->edit(
				$user,
				array_get($inputData, 'first_name'),
				array_get($inputData, 'last_name'),
				array_get($inputData, 'email'),
				array_get($inputData, 'password'),
				$this->request->get('activated', false),
				$this->request->get('groups', []),
				$this->request->get('permissions', [])
			);

			return Redirect::to($this->persistentUrl->route(UsersRouteBinder::SHOW, [$user->getId()]));
		}
		catch (ValidationException $e)
		{
			return Redirect::back()->withInput()->withErrors($e->getErrors());
		}
		catch (PermissionException $e)
		{
			return Redirect::to($this->persistentUrl->route('backoffice.index'));
		}
	}

	public function destroy($id)
	{
		try
		{
			$user = $this->userService->find($id);

			// Try to destroy the entity
			$this->userService->delete($id);

			// Redirect to the listing
			return Redirect::to($this->persistentUrl->route(UsersRouteBinder::INDEX))->withSuccess(
				trans('backoffice::default.delete_msg', ['model' => $this->title, 'id' => $user->getEmail()])
			);
		}
		catch (ValidationException $e)
		{
			return Redirect::back()->withDanger(implode('<br/>', $e->getErrors()));
		}
		catch(PermissionException $e)
		{
			return Redirect::to($this->persistentUrl->route('backoffice.index'))->withDanger(
				trans('backoffice::auth.permission_error')
			);
		}
	}

	public function export()
	{
		$list = $this->getListing();

		$list->fill($this->getData(null));

		$columns = $list->columns()->hide(['id', 'first_name', 'last_name'])->sortable([]);
		$rows = $list->rows();

		$fileName = (new \DateTime())->format('Y-m-d') . '_' . $this->titlePlural;

		$this->excelExporter->create(\Str::slug($fileName), function($excel) use ($columns, $rows) {
			$excel->sheet($this->titlePlural, function($sheet) use ($columns, $rows) {
				$sheet->loadView('backoffice::lists.export', [
					'bulkActions' => [],
					'rowActions' => [],
					'columns' => $columns->visible(),
					'items' => $rows
				]);
			});
		})->download('xls');
	}

	public function resetPassword($id)
	{
		$user = $this->userService->find($id);

		$this->emailer->sendPasswordReset(
			$user,
			route(AuthRouteBinder::RESET_PASSWORD, [$user->getId(), $user->getResetPasswordCode()])
		);

		return Redirect::back()->withSuccess(trans('backoffice::auth.reset-password.email-sent', ['email' => $user->getEmail()]));
	}

	public function resendActivation($id)
	{
		$user = $this->userService->find($id);

		$this->emailer->sendActivation(
			$user,
			route(AuthRouteBinder::ACTIVATE, [$user->getActivationCode()])
		);

		return Redirect::back()->withSuccess(trans('backoffice::auth.activation.email-sent', ['email' => $user->getEmail()]));
	}

	protected function buildForm($target, $label, $method = 'POST', $cancelAction = '', $options = [])
	{
		$form = $this->backoffice->form($target, $label, $method, $cancelAction, $options);

		$inputs = $form->inputs();

		$inputs->text('first_name',    trans('backoffice::auth.first_name'));
		$inputs->text('last_name',     trans('backoffice::auth.last_name'));
		$inputs->text('email',         trans('backoffice::auth.email'));
		$inputs->password('password',  trans('backoffice::auth.password'));
		$inputs->checkbox('activated', trans('backoffice::auth.activated'));

		$groups = $this->groupService->all();

		$options = [];
		$groupPermissions = [];
		foreach ($groups as $group)
		{
			/** @type Group $group */
			$options[$group->getId()] = $group->getName();

			$groupPermissions[$group->getId()] = array_map(function($permission){
				return (string) $permission;
			}, $group->getPermissions());
		}

		$inputs->dropdown(
			'groups',
			trans('backoffice::auth.groups'),
			$options,
			[
				'placeholder'      => trans('backoffice::auth.groups'),
				'multiple'         => 'multiple',
				'class'            => 'user-groups form-control',
				'data-permissions' => json_encode($groupPermissions)
			]
		);

		$permissions = $this->permissionsRepository->all();
		$inputs->dropdown(
			'permissions',
			trans('backoffice::auth.permissions'),
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

		$filters->string('email',      trans('backoffice::auth.email'),      ['class' => 'form-control']);
		$filters->string('first_name', trans('backoffice::auth.first_name'), ['class' => 'form-control']);
		$filters->string('last_name',  trans('backoffice::auth.last_name'),  ['class' => 'form-control']);
		$filters->boolean('activated', trans('backoffice::auth.activated'),  ['class' => 'form-control']);
	}

	/**
	 * @return mixed
	 */
	protected function getListing()
	{
		\Carbon\Carbon::setToStringFormat(trans('backoffice::default.datetime_format'));

		$listing = $this->backoffice->listing([
			'first_name'  => trans('backoffice::auth.first_name'),
			'last_name' => trans('backoffice::auth.last_name'),
			'email'      => trans('backoffice::auth.email'),
			'activated'  => trans('backoffice::auth.activated'),
			'last_login' => trans('backoffice::auth.last_login'),
			'id',
		]);

		$columns = $listing->columns();
		$columns
			->hide([
				'id',
			])
			->sortable([
				'first_name',
				'last_name',
				'email',
				'activated',
				'last_login'
		]);

		return $listing;
	}

	protected function buildListActions(Listing $list)
	{
		$actions = $this->backoffice->actions();

		try {
			$actions->link($this->persistentUrl->route(UsersRouteBinder::CREATE), FontAwesome::icon('plus') . ' ' . trans('backoffice::default.new', ['model' => $this->title]), ['class' => 'btn btn-primary']);
		} catch (PermissionException $e) { /* Do nothing */}
		try {
			$actions->link($this->persistentUrl->route(UsersRouteBinder::EXPORT, Input::all()), FontAwesome::icon('file-excel-o') . ' ' . trans('backoffice::default.export'), ['class' => 'btn btn-success']);
		} catch (PermissionException $e) { /* Do nothing */}

		$list->setActions($actions);

		$rowActions = $this->backoffice->actions();

		// View icon
		$rowActions->link(function(Collection $row) {
			try {
				return $this->persistentUrl->route(UsersRouteBinder::SHOW, $row['id']);
			} catch (PermissionException $e) { return false; }
		}, FontAwesome::icon('eye'), ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => trans('backoffice::default.show')]);

		// Edit icon
		$rowActions->link(function(Collection $row){
			try {
				return $this->persistentUrl->route(UsersRouteBinder::EDIT, $row['id']);
			} catch (PermissionException $e) { return false; }
		}, FontAwesome::icon('edit'), ['class' => 'text-success', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => trans('backoffice::default.edit')]);

		// Delete icon
		$rowActions->form(
			function(Collection $row){
				try {
				return $this->persistentUrl->route(UsersRouteBinder::DESTROY, $row['id']);
				} catch (PermissionException $e) { return false; }
			},
			FontAwesome::icon('times'),
			'DELETE',
			[
				'class'          => 'text-danger',
				'data-toggle'    => 'tooltip',
				'data-placement' => 'top',
				'data-confirm'   => trans('backoffice::default.delete-confirm'),
				'title'          => trans('backoffice::default.delete')
			],
			true
		);

		$rowActions->form(
			function(Collection $row){
				try {
				return $this->persistentUrl->route(UsersRouteBinder::RESET_PASSWORD, $row['id']);
				} catch (PermissionException $e) { return false; }
			},
			FontAwesome::icon('unlock-alt'),
			'POST',
			[
				'class'          => 'text-warning',
				'data-toggle'    => 'tooltip',
				'data-placement' => 'top',
				'data-confirm'   => trans('backoffice::auth.reset-password.confirm'),
				'title'          => trans('backoffice::auth.reset-password.title')
			]
		);

		$rowActions->form(
			function(Collection $row){
				if ($row['activated']) return false;
				try {
					return $this->persistentUrl->route(UsersRouteBinder::RESEND_ACTIVATION, $row['id']);
				} catch (PermissionException $e) { return false; }
			},
			FontAwesome::icon('reply-all'),
			'POST',
			[
				'class'          => 'text-primary',
				'data-toggle'    => 'tooltip',
				'data-placement' => 'top',
				'data-confirm'   => trans('backoffice::auth.activation.confirm'),
				'title'          => trans('backoffice::auth.activation.title')
			]
		);

		$list->setRowActions($rowActions);
	}

	/**
	 * @param int $limit
	 * @return array
	 */
	protected function getData($limit = 10)
	{
		return $this->paginatorFactory->fromDoctrinePaginator(
			$this->userService->search(
	            $this->request->get('email') ?: null,
				$this->request->get('first_name') ?: null,
				$this->request->get('last_name') ?: null,
				$this->request->get('activated') ? (strtolower($this->request->get('activated')) == 'true') : null,
	            camel_case($this->request->get('sort_by')) ?: 'firstName',
	            $this->request->get('sort_sense') ?: 'asc',
	            $limit,
				($this->request->get('page', 1) - 1) * $limit
            )
		);
	}

	/**
	 * @param array $data
	 * @param User $userToUpdate
	 */
    protected function validate($data, User $userToUpdate = null)
    {
        $rules = [
            'email'    => 'required|email|user_email_unique',
            'password' => ($userToUpdate !== null ? 'sometimes|' : '') . 'required|min:3'
        ];

	    $userService = $this->userService;
	    \Validator::extend('user_email_unique', function($attribute, $value, $parameters) use ($userService, $userToUpdate)
	    {
		    try
		    {
			    $user = $userService->findByLogin($value);
			    return $user === null || ($userToUpdate !== null && $userToUpdate->getEmail() == $value);
		    }
		    catch (UserNotFoundException $e)
		    {
			    return true;
		    }
	    });

        $messages = [
            'email.required'    => trans('backoffice::auth.validation.user.email-required'),
	        'email.user_email_unique' => trans('backoffice::auth.validation.user.user-email-repeated'),
            'password.required' => trans('backoffice::auth.validation.user.password-required')
        ];

	    /* @var $validator \Illuminate\Validation\Validator */
        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails())
        {
            throw new ValidationException($validator->errors());
        }
    }
}
