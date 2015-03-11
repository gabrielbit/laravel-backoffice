<?php namespace Digbang\L4Backoffice\Auth;

use Digbang\FontAwesome\Facade as FontAwesome;
use Digbang\L4Backoffice\Auth\Routes\GroupsRouteBinder;
use Digbang\L4Backoffice\Backoffice;
use Digbang\L4Backoffice\Exceptions\ValidationException;
use Digbang\L4Backoffice\Listings\Listing;
use Digbang\L4Backoffice\Support\PermissionParser;
use Digbang\Security\Permissions\Exceptions\PermissionException;
use Digbang\Security\Permissions\PermissionRepository;
use Digbang\Security\Services\GroupService;
use Digbang\Security\Urls\SecureUrl;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Excel;

class GroupController extends Controller
{
	/**
     * @var \Digbang\L4Backoffice\Backoffice
     */
    protected $backoffice;

	/**
	 * @type PermissionRepository
	 */
	protected $permissionsRepository;

	/**
     * @var \Maatwebsite\Excel\Excel
     */
    protected $excelExporter;

	/**
	 * @type PermissionParser
	 */
	protected $permissionParser;

	/**
	 * @type SecureUrl
	 */
	protected $secureUrl;

	/**
	 * @type GroupService
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

	/**
	 * @type Request
	 */
	private $request;

	/**
	 * @param Backoffice           $backoffice
	 * @param Excel                $excelExporter
	 * @param PermissionRepository $permissionRepository
	 * @param PermissionParser     $permissionParser
	 * @param SecureUrl            $secureUrl
	 * @param GroupService         $groupService
	 * @param Request              $request
	 */
	public function __construct(
		Backoffice           $backoffice,
		Excel                $excelExporter,
		PermissionRepository $permissionRepository,
		PermissionParser     $permissionParser,
		SecureUrl            $secureUrl,
		GroupService         $groupService,
		Request              $request
	)
	{
		$this->backoffice            = $backoffice;
		$this->excelExporter         = $excelExporter;
		$this->permissionsRepository = $permissionRepository;
		$this->permissionParser      = $permissionParser;
		$this->secureUrl             = $secureUrl;
		$this->groupService          = $groupService;
		$this->request               = $request;

		$this->title = trans('l4-backoffice::auth.group');
		$this->titlePlural = trans('l4-backoffice::auth.groups');
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
			$this->secureUrl->route(GroupsRouteBinder::STORE),
			$label,
			'POST',
			$this->secureUrl->route(GroupsRouteBinder::INDEX)
		);

		$breadcrumb = $this->backoffice->breadcrumb([
			trans('l4-backoffice::default.home') => 'backoffice.index',
			$this->titlePlural => GroupsRouteBinder::INDEX,
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
		$input = $this->request->only(['name', 'permissions']);

		try
		{
			$this->validate($input);

			$groups = $this->groupService->create(
				$input['name'],
				$input['permissions']
			);

			$groups->save();

			return Redirect::to($this->secureUrl->route(GroupsRouteBinder::SHOW, $groups->getId()));
		}
		catch (ValidationException $e)
		{
			return Redirect::back()->withInput()->withErrors($e->getErrors());
		}
	}

	public function show($id)
	{
		$group = $this->groupService->find($id);

		$breadcrumb = $this->backoffice->breadcrumb([
			trans('l4-backoffice::default.home') => 'backoffice.index',
			$this->titlePlural => GroupsRouteBinder::INDEX,
			$group->getName()
		]);

		$data = [
			'Name' => $group->getName(),
			'Permissions' => $this->permissionParser->toViewTable($this->permissionsRepository->all(), $group),
		];

		$actions = $this->backoffice->actions()
			->link($this->secureUrl->route(GroupsRouteBinder::EDIT, $id), FontAwesome::icon('edit') . ' ' . trans('l4-backoffice::default.edit'), ['class' => 'btn btn-success'])
			->link($this->secureUrl->route(GroupsRouteBinder::INDEX), trans('l4-backoffice::default.back'), ['class' => 'btn btn-default']);

		$topActions = $this->backoffice->actions()
			->link($this->secureUrl->route(GroupsRouteBinder::INDEX), FontAwesome::icon('arrow-left') . ' ' . trans('l4-backoffice::default.back'));

		return View::make('l4-backoffice::show', [
			'title'      => $this->titlePlural,
			'breadcrumb' => $breadcrumb,
			'label'      => $group->getName(),
			'data'       => $data,
			'actions'    => $actions,
			'topActions' => $topActions
		]);
	}

	public function edit($id)
	{
		$group = $this->groupService->find($id);

		$label = trans('l4-backoffice::default.edit_model', ['model' => $this->title]);

		$form = $this->buildForm(
			$this->secureUrl->route(GroupsRouteBinder::UPDATE, $id),
			$label,
			'PUT',
			$this->secureUrl->route(GroupsRouteBinder::SHOW, $id)
		);

		$form->fill([
			'name' => $group->getName(),
			'permissions[]' => $group->getPermissions(),
		]);

		$breadcrumb = $this->backoffice->breadcrumb([
			trans('l4-backoffice::default.home') => 'backoffice.index',
			$this->titlePlural => GroupsRouteBinder::INDEX,
			$group->getName()  => [GroupsRouteBinder::SHOW, $id],
			$label
		]);

		return View::make('l4-backoffice::edit', [
			'title'      => $label,
			'form'       => $form,
			'breadcrumb' => $breadcrumb
		]);
	}

	public function update($id)
	{
		// Get the input
		$input = $this->request->only(['name', 'permissions']);

		try
		{
			// Validate the input
			$this->validate($input);

			$group = $this->groupService->find($id);

			$this->groupService->edit(
				$group,
				$input['name'],
				$input['permissions']
			);

			// Redirect to show
			return Redirect::to($this->secureUrl->route(GroupsRouteBinder::SHOW, [$group->getId()]));
		}
		catch (ValidationException $e)
		{
			// Or redirect back with the errors
			return Redirect::back()->withInput()->withErrors($e->getErrors());
		}
	}

	public function destroy($id)
	{
		try
		{
			// Try to destroy the entity
			$this->groupService->delete($id);

			// Redirect to the listing
			return Redirect::to($this->secureUrl->route(GroupsRouteBinder::INDEX))
				->withSuccess(trans('l4-backoffice::default.delete_msg', ['model' => $this->title, 'id' => $id]));
		}
		catch (ValidationException $e)
		{
			return Redirect::back()->withDanger(implode('<br/>', $e->getErrors()));
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

		$inputs->text('name', trans('l4-backoffice::auth.name'));
		$inputs->dropdown(
			'permissions',
			trans('l4-backoffice::auth.permissions'),
			$this->permissionParser->toDropdownArray($this->permissionsRepository->all()),
			['multiple' => 'multiple', 'class' => 'multiselect']
		);

		return $form;
	}

	/**
	 * @param $list
	 */
	protected function buildFilters(Listing $list)
	{
		$filters = $list->filters();

		// Here we add filters to the list
		$filters->string('name', trans('l4-backoffice::auth.name'), ['class' => 'form-control']);
		$filters->dropdown(
			'permission',
			trans('l4-backoffice::auth.permissions'),
			$this->permissionParser->toDropdownArray($this->permissionsRepository->all()),
			['class' => 'form-control']
		);
	}

	/**
	 * @return mixed
	 */
	protected function getListing()
	{
		$listing = $this->backoffice->listing([
			'name' => trans('l4-backoffice::auth.name'),
			'id'
		]);

		$columns = $listing->columns();
		$columns->hide(['id'])->sortable(['name']);

		return $listing;
	}

	protected function buildListActions(Listing $list)
	{
		$list->setActions(
			$this->backoffice->actions()
				->link($this->secureUrl->route(GroupsRouteBinder::CREATE), FontAwesome::icon('plus') . trans('l4-backoffice::default.new', ['model' => $this->title]), ['class' => 'btn btn-primary'])
				->link($this->secureUrl->route(GroupsRouteBinder::EXPORT, $this->request->all()), FontAwesome::icon('file-excel-o') . ' ' . trans('l4-backoffice::default.export'), ['class' => 'btn btn-success'])
		);

		$list->setRowActions(
			$this->backoffice->actions()
				// View icon
				->link(function(Collection $row) {
					try {
						return $this->secureUrl->route(GroupsRouteBinder::SHOW, $row['id']);
					} catch (PermissionException $e) { return false; }
				}, FontAwesome::icon('eye'), ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => trans('l4-backoffice::default.show')])
				// Edit icon
				->link(function(Collection $row){
					try {
						return $this->secureUrl->route(GroupsRouteBinder::EDIT, $row['id']);
					} catch (PermissionException $e) { return false; }
				}, FontAwesome::icon('edit'), ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => trans('l4-backoffice::default.edit')])
				// Delete icon
				->form(
					function(Collection $row){
						try {
							return $this->secureUrl->route(GroupsRouteBinder::DESTROY, $row['id']);
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
				)
		);
	}

	/**
	 * @param int $limit
	 * @return array
	 */
	protected function getData($limit = 10)
	{
		return $this->groupService->search(
			$this->request->get('name'),
			$this->request->get('permission'),
            camel_case($this->request->get('sort_by')),
            $this->request->get('sort_sense'),
            $limit,
			($this->request->get('page', 1) - 1) * $limit
        );
	}

	/**
	 * @param array $data
	 *
	 * @throws ValidationException
	 */
    protected function validate($data)
    {
        $rules = ['name' => 'required'];

        $messages = [
            'name.required' => trans('l4-backoffice::auth.validation.group.name'),
        ];

	    /** @type \Illuminate\Validation\Validator $validator */
        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails())
        {
            throw new ValidationException($validator->errors());
        }
    }
}
