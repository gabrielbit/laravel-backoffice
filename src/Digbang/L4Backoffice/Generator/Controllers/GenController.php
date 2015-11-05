<?php namespace Digbang\L4Backoffice\Generator\Controllers;

use Digbang\L4Backoffice\Backoffice;
use Digbang\L4Backoffice\Generator\Services\ControllerGenerator;
use Digbang\L4Backoffice\Generator\Services\ApiFinder;
use Digbang\L4Backoffice\Inputs\InputFactory;
use Illuminate\Config\Repository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Session\Store;
use Illuminate\View\Factory as ViewFactory;

/**
 * Class GenController
 * @package Digbang\L4Backoffice\Generator\Controllers
 */
class GenController extends Controller
{
	protected $backoffice;
	protected $apiFinder;
	protected $session;
	protected $generator;

	/**
	 * @type ViewFactory
	 */
	private $view;
	/**
	 * @type UrlGenerator
	 */
	private $url;
	/**
	 * @type Repository
	 */
	private $config;
	/**
	 * @type Request
	 */
	private $request;
	/**
	 * @type InputFactory
	 */
	private $inputFactory;

	public function __construct(Backoffice $backoffice, ApiFinder $apiFinder, ControllerGenerator $generator, Store $session, ViewFactory $view, UrlGenerator $url, Repository $config, Request $request, InputFactory $inputFactory)
	{
		$this->backoffice = $backoffice;
		$this->apiFinder = $apiFinder;
		$this->generator = $generator;
		$this->session = $session;
		$this->view = $view;
		$this->url = $url;
		$this->config = $config;
		$this->request = $request;
		$this->inputFactory = $inputFactory;
	}

	public function modelSelection()
	{
		// Look for available backoffice APIs
		$apis = $this->apiFinder->all($this->config->get('backoffice::gen.apis_path'));

		// Build a form with checkboxes for each of them
		$form = $this->backoffice->form(
			$this->url->action(GenController::class . '@customization'),
			'Choose APIs',
			'POST'
		);

		foreach ($apis as $api)
		{
			$form->inputs()->checkbox($api, $api);
		}

		$title = 'Gen';

		$breadcrumb = $this->backoffice->breadcrumb([
			'Home' => $this->url->route('backoffice.index'),
			$title
		]);

		return $this->view->make('backoffice::gen.model-selection', [
			'title'      => $title,
			'breadcrumb' => $breadcrumb,
			'form'       => $form
		]);
	}

	public function customization()
	{
		// Save selected apis in session
		$this->session->put('backoffice.gen.apis', $this->request->except('_token'));

		// Build a form with customization options
		$form = $this->backoffice->form(
			$this->url->action(GenController::class . '@generation'),
			'Customize generated resources',
			'POST'
		);

		$inputs = $form->inputs();

		$inputs->text('controller_namespace', 'Controllers Namespace');
		$inputs->find('controller_namespace')->defaultsTo(
			$this->config->get('backoffice::gen.controllers_namespace')
		);
		$inputs->text('controllers_dir', 'Controllers Directory');
		$inputs->find('controllers_dir')->defaultsTo(
			$this->config->get('backoffice::gen.controllers_dir')
		);

		foreach (array_keys($this->request->except('_token')) as $api)
		{
			$methods = $this->apiFinder->parseMethods($api);

			$methodsDropdown = ['DO_NOT_CREATE' => 'Do not create'];

			foreach ($methods as $method)
			{
				$methodsDropdown[$method] = $api . ' :: ' . $method;
			}

			$collection = $this->inputFactory->collection();

			foreach (['index', 'create', 'read', 'update', 'delete', 'export'] as $crudMethod)
			{
				$collection->text($crudMethod);
				$collection->dropdown("apis[$api][$crudMethod]", $crudMethod . ' Method', $methodsDropdown);

				/** @type \Digbang\L4Backoffice\Inputs\Input $literal */
				$literal = $collection->find($crudMethod);

				$literal->defaultsTo("Create a $crudMethod page?");
				$literal->setReadonly();
			}

			$inputs->composite($api, $collection, $api);
		}

		$title = 'Customize';

		$breadcrumb = $this->backoffice->breadcrumb([
			'Home' => $this->url->route('backoffice.index'),
			'Gen'  => $this->url->action(GenController::class . '@modelSelection'),
			$title
		]);

		// Return it
		return $this->view->make('backoffice::gen.customization', [
			'title'      => $title,
			'breadcrumb' => $breadcrumb,
			'form'       => $form
		]);
	}

	public function generation()
	{
		$controllerNamespace = trim($this->request->get('controller_namespace'), ' \\');
		$controllersDir      = trim($this->request->get('controllers_dir'));
		$templatePath        = realpath(__DIR__ . '/../controller.mustache');

		$this->generator
			->fromTemplate($templatePath)
			->toDir($controllersDir)
			->inNamespace($controllerNamespace);

		$apis = $this->request->get('apis');

		$controllers = [];
		$apisInControllers = [];

		foreach ($apis as $api => $methods)
		{
			$this->generator->withApi($api);

			foreach ($methods as $method => $apiMethod)
			{
				if ($apiMethod == 'DO_NOT_CREATE')
				{
					continue;
				}

				$params = $this->apiFinder->getParamsFor($api, $apiMethod);

				$this->generator->addMethod($method, $apiMethod, $params);
			}

			$controller = $this->generator->generate();
			$controllers[$controller] = array_keys($methods);
			$apisInControllers[$controller] = $api;
		}

		$title = 'Gen complete';

		$breadcrumb = $this->backoffice->breadcrumb([
			'Home' => route('backoffice.index'),
			'Gen'  => action(GenController::class . '@modelSelection'),
			$title
		]);

		// Return
		return $this->view->make('backoffice::gen.generation', [
			'title' => $title,
			'breadcrumb' => $breadcrumb,
			'controllers' => $controllers,
			'apis' => $apisInControllers
		]);
	}

	public function testGenerationPage()
	{
		$title = 'Gen complete';

		$breadcrumb = $this->backoffice->breadcrumb([
			'Home' => route('backoffice.index'),
			'Gen' => action(GenController::class . '@modelSelection'),
			$title
		]);

		return View::make('backoffice::gen.generation', [
			'title' => $title,
			'breadcrumb' => $breadcrumb,
			'tables' => ['foos', 'bars', 'bazes', 'a_really_long_tables'],
			'backofficeNamespace' => 'A\\\\Really\\\\Long\\\\Namespace'
		]);
	}
}