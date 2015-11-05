<?php namespace Digbang\L4Backoffice\Auth\Controllers;

use Digbang\Doctrine\Tools\PaginatorFactory;
use Digbang\FontAwesome\Facade as FontAwesome;
use Digbang\L4Backoffice\Auth\Routes\GroupsRouteBinder;
use Digbang\L4Backoffice\Backoffice;
use Digbang\L4Backoffice\Exceptions\ValidationException;
use Digbang\L4Backoffice\Listings\Listing;
use Digbang\L4Backoffice\Support\PermissionParser;
use Digbang\L4Backoffice\Urls\PersistentUrl;
use Digbang\Security\Permissions\Exceptions\PermissionException;
use Digbang\Security\Permissions\PermissionRepository;
use Digbang\Security\Services\GroupService;
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
	 * @type PersistentUrl
	 */
	protected $persistentUrl;

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
	 * @type PaginatorFactory
	 */
	private $paginatorFactory;

	/**
	 * @param Backoffice           $backoffice
	 * @param Excel                $excelExporter
	 * @param PermissionRepository $permissionRepository
	 * @param PermissionParser     $permissionParser
	 * @param PersistentUrl            $persistentUrl
	 * @param GroupService         $groupService
	 * @param Request              $request
	 * @param PaginatorFactory     $paginatorFactory
	 */
	public function __construct(
		Backoffice           $backoffice,
		Excel                $excelExporter,
		PermissionRepository $permissionRepository,
		PermissionParser     $permissionParser,
		PersistentUrl        $persistentUrl,
		GroupService         $groupService,
		Request              $request,
		PaginatorFactory     $paginatorFactory
	)
	{
		$this->backoffice            = $backoffice;
		$this->excelExporter         = $excelExporter;
		$this->permissionsRepository = $permissionRepository;
		$this->permissionParser      = $permissionParser;
		$this->persistentUrl             = $persistentUrl;
		$this->groupService          = $groupService;
		$this->request               = $request;
		$this->paginatorFactory      = $paginatorFactory;

		$this->title = trans('backoffice::auth.group');
		$this->titlePlural = trans('backoffice::auth.groups');
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
			$this->persistentUrl->route(GroupsRouteBinder::STORE),
			$label,
			'POST',
			$this->persistentUrl->route(GroupsRouteBinder::INDEX)
		);

		$breadcrumb = $this->backoffice->breadcrumb([
			trans('backoffice::default.home') => 'backoffice.index',
			$this->titlePlural => GroupsRouteBinder::INDEX,
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
		$input = $this->request->only(['name', 'permissions']);

		try
		{
			$this->validate($input);

			$groups = $this->groupService->create(
				$input['name'],
				(array)$input['permissions']
			);

			$groups->save();

			return Redirect::to($this->persistentUrl->route(GroupsRouteBinder::SHOW, $groups->getId()));
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
			trans('backoffice::default.home') => 'backoffice.index',
			$this->titlePlural => GroupsRouteBinder::INDEX,
			$group->getName()
		]);

		$data = [
			trans('backoffice::auth.name') => $group->getName(),
			trans('backoffice::auth.permissions') => $this->permissionParser->toViewTable($this->permissionsRepository->all(), $group),
		];

		$actions = $this->backoffice->actions()
			->link($this->persistentUrl->route(GroupsRouteBinder::EDIT, $id), FontAwesome::icon('edit') . ' ' . trans('backoffice::default.edit'), ['class' => 'btn btn-success'])
			->link($this->persistentUrl->route(GroupsRouteBinder::INDEX), trans('backoffice::default.back'), ['class' => 'btn btn-default']);

		$topActions = $this->backoffice->actions()
			->link($this->persistentUrl->route(GroupsRouteBinder::INDEX), FontAwesome::icon('arrow-left') . ' ' . trans('backoffice::default.back'));

		return View::make('backoffice::show', [
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

		$form = $this->buildForm(
			$this->persistentUrl->route(GroupsRouteBinder::UPDATE, $id),
			trans('backoffice::default.edit') . ' ' . $group->getName(),
			'PUT',
			$this->persistentUrl->route(GroupsRouteBinder::SHOW, $id)
		);

		$form->fill([
			'name' => $group->getName(),
			'permissions[]' => $group->getPermissions(),
		]);

		$breadcrumb = $this->backoffice->breadcrumb([
			trans('backoffice::default.home') => 'backoffice.index',
			$this->titlePlural => GroupsRouteBinder::INDEX,
			$group->getName()  => [GroupsRouteBinder::SHOW, $id],
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
				(array)$input['permissions']
			);

			// Redirect to show
			return Redirect::to($this->persistentUrl->route(GroupsRouteBinder::SHOW, [$group->getId()]));
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
			$group = $this->groupService->find($id);

			// Try to destroy the entity
			$this->groupService->delete($id);

			// Redirect to the listing
			return Redirect::to($this->persistentUrl->route(GroupsRouteBinder::INDEX))
				->withSuccess(trans('backoffice::default.delete_msg', ['model' => $this->title, 'id' => $group->getName()]));
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

		$columns = $list->columns()->hide(['id'])->sortable([]);
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

	protected function buildForm($target, $label, $method = 'POST', $cancelAction = '', $options = [])
	{
		$form = $this->backoffice->form($target, $label, $method, $cancelAction, $options);

		$inputs = $form->inputs();

		$inputs->text('name', trans('backoffice::auth.name'));
		$inputs->dropdown(
			'permissions',
			trans('backoffice::auth.permissions'),
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
		$filters->string('name', trans('backoffice::auth.name'), ['class' => 'form-control']);
		$filters->dropdown(
			'permission',
			trans('backoffice::auth.permissions'),
			$this->permissionParser->toDropdownArray($this->permissionsRepository->all(), true),
			['class' => 'form-control']
		);
	}

	/**
	 * @return mixed
	 */
	protected function getListing()
	{
		$listing = $this->backoffice->listing([
			'name' => trans('backoffice::auth.name'),
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
				->link($this->persistentUrl->route(GroupsRouteBinder::CREATE), FontAwesome::icon('plus') . ' ' . trans('backoffice::default.new', ['model' => $this->title]), ['class' => 'btn btn-primary'])
				->link($this->persistentUrl->route(GroupsRouteBinder::EXPORT, $this->request->all()), FontAwesome::icon('file-excel-o') . ' ' . trans('backoffice::default.export'), ['class' => 'btn btn-success'])
		);

		$list->setRowActions(
			$this->backoffice->actions()
				// View icon
				->link(function(Collection $row) {
					try {
						return $this->persistentUrl->route(GroupsRouteBinder::SHOW, $row['id']);
					} catch (PermissionException $e) { return false; }
				}, FontAwesome::icon('eye'), ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => trans('backoffice::default.show')])
				// Edit icon
				->link(function(Collection $row){
					try {
						return $this->persistentUrl->route(GroupsRouteBinder::EDIT, $row['id']);
					} catch (PermissionException $e) { return false; }
				}, FontAwesome::icon('edit'), ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => trans('backoffice::default.edit')])
				// Delete icon
				->form(
					function(Collection $row){
						try {
							return $this->persistentUrl->route(GroupsRouteBinder::DESTROY, $row['id']);
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
				)
		);
	}

	/**
	 * @param int $limit
	 * @return array
	 */
	protected function getData($limit = 10)
	{
		return $this->paginatorFactory->fromDoctrinePaginator(
			$this->groupService->search(
				$this->request->get('name') ?: null,
				$this->request->get('permission') ?: null,
	            camel_case($this->request->get('sort_by')) ?: 'name',
	            $this->request->get('sort_sense') ?: 'asc',
	            $limit,
				($this->request->get('page', 1) - 1) * $limit
	        )
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
            'name.required' => trans('backoffice::auth.validation.group.name'),
        ];

	    /** @type \Illuminate\Validation\Validator $validator */
        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails())
        {
            throw new ValidationException($validator->errors());
        }
    }
}
