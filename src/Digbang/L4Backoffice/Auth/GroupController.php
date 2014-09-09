<?php namespace Digbang\L4Backoffice\Auth;

use Digbang\Security\Entities\Group;
use Digbang\L4Backoffice\Backoffice;
use Digbang\L4Backoffice\Repositories\BackofficeRepositoryFactory;
use Digbang\L4Backoffice\Listings\Listing;
use Digbang\L4Backoffice\Exceptions\ValidationException;
use Digbang\L4Backoffice\Support\PermissionParser;
use Digbang\Security\Permissions\Exceptions\PermissionException;
use Digbang\Security\Permissions\PermissionRepository;
use Digbang\Security\Urls\SecureUrl;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Digbang\FontAwesome\Facade as FontAwesome;
use Maatwebsite\Excel\Excel;

class GroupController extends Controller
{
	/**
     * @var \Digbang\L4Backoffice\Backoffice
     */
    protected $backoffice;

    /**
     * @var \Digbang\L4Backoffice\Repositories\BackofficeRepository
     */
    protected $groupsRepository;

	protected $permissionsRepository;

	/**
     * @var \Maatwebsite\Excel\Excel
     */
    protected $excelExporter;
	protected $permissionParser;
	protected $secureUrl;

    protected $title = 'Group';
    protected $titlePlural = 'Groups';

	function __construct(Backoffice $backoffice, BackofficeRepositoryFactory $repositoryFactory, Excel $excelExporter, PermissionRepository $permissionRepository, PermissionParser $permissionParser, SecureUrl $secureUrl)
	{
		$this->backoffice = $backoffice;
		$this->groupsRepository = $repositoryFactory->makeForEloquentModel(new Group());
		$this->permissionsRepository = $permissionRepository;

		$this->excelExporter = $excelExporter;
		$this->permissionParser = $permissionParser;
		$this->secureUrl = $secureUrl;
	}

	public function index()
	{
		$list = $this->getListing();

		$this->buildFilters($list);

		$this->buildListActions($list);

		$list->fill($this->getData());

		$breadcrumb = $this->backoffice->breadcrumb([
				'Home' => 'backoffice.index',
				$this->titlePlural
		]);

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
			$this->secureUrl->route('backoffice.backoffice-groups.store'),
			$label,
			'POST',
			$this->secureUrl->route('backoffice.backoffice-groups.index')
		);

		$breadcrumb = $this->backoffice->breadcrumb([
			'Home'             => 'backoffice.index',
			$this->titlePlural => 'backoffice.backoffice-groups.index',
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
            'name',
            'permissions',
        ]), function($input){
            return !empty($input) && $input !== false;
        });

		try
		{
			$this->validate($inputData);

			/* @var $groups Group */
			$groups = $this->groupsRepository->create($inputData);

			$permissions = \Input::get('permissions');

			$allPermissions = $this->permissionsRepository->all();
			$allPermissions = array_combine($allPermissions, array_map(function($permission) use ($permissions) {
				return (integer) in_array($permission, $permissions);
			}, $allPermissions));

			$groups->permissions = $allPermissions;

			$groups->save();

			return \Redirect::to($this->secureUrl->route('backoffice.backoffice-groups.show', $groups->getKey()));
		}
		catch (ValidationException $e)
		{
			return \Redirect::back()->withInput()->withErrors($e->getErrors());
		}
	}

	public function show($id)
	{
		/* @var $entity Group */
		$entity = $this->groupsRepository->findById($id);

		$breadcrumb = $this->backoffice->breadcrumb([
			'Home'             => 'backoffice.index',
			$this->titlePlural => 'backoffice.backoffice-groups.index',
			$entity->name
		]);

		$data = [
			'Name' => $entity->name,
			'Permissions' => $this->permissionParser->toViewTable($this->permissionsRepository->all(), $entity),
		];

		$actions = $this->backoffice->actions()
			->link($this->secureUrl->route('backoffice.backoffice-groups.edit', $id), FontAwesome::icon('edit') . ' ' . \Lang::get('l4-backoffice::default.edit'), ['class' => 'btn btn-success'])
			->link($this->secureUrl->route('backoffice.backoffice-groups.index'), \Lang::get('l4-backoffice::default.back'), ['class' => 'btn btn-default']);

		$topActions = $this->backoffice->actions()
			->link($this->secureUrl->route('backoffice.backoffice-groups.index'), FontAwesome::icon('arrow-left') . ' ' . \Lang::get('l4-backoffice::default.back'));

		return \View::make('l4-backoffice::show', [
			'title'      => $this->titlePlural,
			'breadcrumb' => $breadcrumb,
			'label'      => $entity->name,
			'data'       => $data,
			'actions'    => $actions,
			'topActions' => $topActions
		]);
	}

	public function edit($id)
	{
		/* @var $entity Group */
		$entity = $this->groupsRepository->findById($id);

		$label = \Lang::get('l4-backoffice::default.edit');

		$form = $this->buildForm(
			$this->secureUrl->route('backoffice.backoffice-groups.update', $id),
			$label,
			'PUT',
			$this->secureUrl->route('backoffice.backoffice-groups.show', $id)
		);

		$form->fill([
			'name' => $entity->name,
			'permissions[]' => array_keys($entity->permissions),
		]);

		$breadcrumb = $this->backoffice->breadcrumb([
			'Home'             => 'backoffice.index',
			$this->titlePlural => 'backoffice.backoffice-groups.index',
			$entity->name      => ['backoffice.backoffice-groups.show', $id],
			\Lang::get('l4-backoffice::default.edit')
		]);

		return \View::make('l4-backoffice::edit', [
			'title'      => 'Edit Group',
			'form'       => $form,
			'breadcrumb' => $breadcrumb
		]);
	}

	public function update($id)
	{
		/* @var $entity Group */
        $entity = $this->groupsRepository->findById($id);

		// Get the input
		$inputData = array_filter(\Input::only([
            'name'
        ]), function($value){
	         return !empty($value) && $value !== false;
	    });

		try
		{
			// Validate the input
			$this->validate($inputData);

			$entity->name = \Input::get('name');

			$permissions = \Input::get('permissions');

			$allPermissions = $this->permissionsRepository->all();
			$allPermissions = array_combine($allPermissions, array_map(function($permission) use ($permissions) {
				return (integer) in_array($permission, $permissions);
			}, $allPermissions));

			$entity->permissions = $allPermissions;

			// Try to save it
			$entity->save();

			// Redirect to show
			return \Redirect::to($this->secureUrl->route('backoffice.backoffice-groups.show', [$entity->getKey()]));
		}
		catch (ValidationException $e)
		{
			// Or redirect back with the errors
			return \Redirect::back()->withInput()->withErrors($e->getErrors());
		}
	}

	public function destroy($id)
	{
		try
		{
			// Try to destroy the entity
			$this->groupsRepository->destroy($id);

			// Redirect to the listing
			return \Redirect::to($this->secureUrl->route('backoffice.backoffice-groups.index'))->with(['success' => "Group $id deleted"]);
		}
		catch (ValidationException $e)
		{
			return \Redirect::back()->withErrors($e->getErrors());
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

		$inputs->text('name', 'Name');

		$permissions = $this->permissionsRepository->all();
		$inputs->dropdown('permissions', 'Permissions', $this->permissionParser->toDropdownArray($permissions), ['multiple' => 'multiple', 'class' => 'multiselect']);

		return $form;
	}

	/**
	 * @param $list
	 */
	protected function buildFilters(Listing $list)
	{
		$filters = $list->filters();

		// Here we add filters to the list
		$filters->string('name', 'Name', ['class' => 'form-control']);
		$filters->text('permissions', 'Permissions', ['class' => 'form-control']);

		// And filter dependencies
	}

	/**
	 * @return mixed
	 */
	protected function getListing()
	{
		$listing = $this->backoffice->listing([
			'id' => 'Id',
			'name' => 'Name'
		]);

		$columns = $listing->columns();
		$columns->hide(['id'])->sortable(['name']);

		return $listing;
	}

	protected function buildListActions(Listing $list)
	{
		$list->setActions(
			$this->backoffice->actions()
				->link($this->secureUrl->route('backoffice.backoffice-groups.create'), FontAwesome::icon('plus') . ' New Group', ['class' => 'btn btn-primary'])
				->link($this->secureUrl->route('backoffice.backoffice-groups.export', \Input::all()), FontAwesome::icon('file-excel-o') . ' Export', ['class' => 'btn btn-success'])
		);

		$list->setRowActions(
			$this->backoffice->actions()
				// View icon
				->link(function(Collection $row) {
					try {
						return $this->secureUrl->route('backoffice.backoffice-groups.show', $row['id']);
					} catch (PermissionException $e) { return false; }
				}, FontAwesome::icon('eye'), ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => \Lang::get('l4-backoffice::default.show')])
				// Edit icon
				->link(function(Collection $row){
					try {
						return $this->secureUrl->route('backoffice.backoffice-groups.edit', $row['id']);
					} catch (PermissionException $e) { return false; }
				}, FontAwesome::icon('edit'), ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => \Lang::get('l4-backoffice::default.edit')])
				// Delete icon
				->form(
					function(Collection $row){
						try {
							return $this->secureUrl->route('backoffice.backoffice-groups.destroy', $row['id']);
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
				)
		);
	}

	/**
	 * @return array
	 */
	protected function getData($limit = 10)
	{
		return $this->groupsRepository->search(
            \Input::except(['page', 'sort_by', 'sort_sense']),
            \Input::get('sort_by'),
            \Input::get('sort_sense'),
            $limit,
            \Input::get('page', 1)
        );
	}

    protected function validate($inputData)
    {
        $validationRules = [
            'name' => 'required',
        ];

        $validationMsgs = [
            'name.required' => 'The Name field is required.',
        ];

        $validator = \Validator::make($inputData, $validationRules, $validationMsgs);

        if ($validator->fails())
        {
            throw new ValidationException($validator->errors());
        }
    }
}
