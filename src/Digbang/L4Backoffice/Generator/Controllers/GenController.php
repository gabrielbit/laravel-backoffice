<?php namespace Digbang\L4Backoffice\Generator\Controllers;

use Digbang\L4Backoffice\Backoffice;
use Digbang\L4Backoffice\Generator\Services\ControllerGenerator;
use Digbang\L4Backoffice\Generator\Services\ModelFinder;
use Illuminate\Session\Store;

/**
 * Class GenController
 * @package Digbang\L4Backoffice\Generator\Controllers
 */
class GenController extends \Controller
{
	protected $backoffice;
	protected $modelFinder;
	protected $session;
	protected $generator;

	function __construct(Backoffice $backoffice, ModelFinder $modelFinder, ControllerGenerator $generator, Store $session)
	{
		$this->backoffice = $backoffice;
		$this->modelFinder = $modelFinder;
		$this->generator = $generator;
		$this->session = $session;
	}

	public function modelSelection()
	{
		// Grab current database tables
		$tables = $this->modelFinder->find();

		// Build a form with checkboxes for each of them
		$form = $this->backoffice->form(
			action('Digbang\L4Backoffice\Generator\Controllers\GenController@customization'),
			'Choose models',
			'POST'
		);

		foreach ($tables as $table)
		{
			$form->inputs()->checkbox($table, \Str::titleFromSlug($table));
		}

		$title = 'Gen';

		$breadcrumb = $this->backoffice->breadcrumb([
			'Home' => route('backoffice.index'),
			'Gen'
		]);

		// Return it
		return \View::make('l4-backoffice::gen.model-selection', [
			'title' => $title,
			'breadcrumb' => $breadcrumb,
			'form' => $form
		]);
	}

	public function customization()
	{
		// Save selected tables in session
		$this->session->put('backoffice.gen.tables', \Input::except('_token'));

		// Build a form with customization options
		$form = $this->backoffice->form(
			action('Digbang\L4Backoffice\Generator\Controllers\GenController@generation'),
			'Customize generated resources',
			'POST'
		);

		$form->inputs()->text('backoffice_namespace', 'Backoffice Namespace');
		$form->inputs()->text('entities_namespace', 'Entities Namespace');

		$title = 'Customize';

		$breadcrumb = $this->backoffice->breadcrumb([
			'Home' => route('backoffice.index'),
			'Gen' => action('Digbang\\L4Backoffice\\Generator\\Controllers\\GenController@modelSelection'),
			$title
		]);

		// Return it
		return \View::make('l4-backoffice::gen.customization', [
			'title' => $title,
			'breadcrumb' => $breadcrumb,
			'form' => $form
		]);
	}

	public function generation()
	{
		// Iterate over each model
		$tables = $this->session->get('backoffice.gen.tables');

		$backofficeNamespace = trim(\Input::get('backoffice_namespace'), ' \\');
		$entityNamespace = '\\' . trim(\Input::get('entities_namespace'), ' \\') . '\\';

		$controllersDirPath = app_path() . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $backofficeNamespace);
		$templatePath = realpath(
			dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'controller.mustache'
		);

		foreach ($tables as $tableName => $on)
		{
			$this->generator->generate($tableName, $templatePath, $controllersDirPath, $backofficeNamespace, $entityNamespace);
		}

		$title = 'Gen complete';

		$breadcrumb = $this->backoffice->breadcrumb([
			'Home' => route('backoffice.index'),
			'Gen' => action('Digbang\\L4Backoffice\\Generator\\Controllers\\GenController@modelSelection'),
			$title
		]);

		// Return
		return \View::make('l4-backoffice::gen.generation', [
			'title' => $title,
			'breadcrumb' => $breadcrumb,
			'tables' => array_keys($tables),
			'backofficeNamespace' => str_replace('\\', '\\\\', $backofficeNamespace)
		]);
	}

	public function testGenerationPage()
	{
		$title = 'Gen complete';

		$breadcrumb = $this->backoffice->breadcrumb([
			'Home' => route('backoffice.index'),
			'Gen' => action('Digbang\\L4Backoffice\\Generator\\Controllers\\GenController@modelSelection'),
			$title
		]);

		return \View::make('l4-backoffice::gen.generation', [
			'title' => $title,
			'breadcrumb' => $breadcrumb,
			'tables' => ['foo', 'bar', 'baz', 'a_really_long_table'],
			'backofficeNamespace' => 'A\\\\Really\\\\Long\\\\Namespace'
		]);
	}
}