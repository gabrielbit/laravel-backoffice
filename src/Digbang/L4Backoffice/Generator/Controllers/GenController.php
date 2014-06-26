<?php namespace Digbang\L4Backoffice\Generator\Controllers;

use Digbang\L4Backoffice\Backoffice;
use Digbang\L4Backoffice\Generator\Services\ModelFinder;
use Illuminate\Session\Store;
use Way\Generators\Generator;

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

	function __construct(Backoffice $backoffice, ModelFinder $modelFinder, Generator $generator, Store $session)
	{
		$this->backoffice = $backoffice;
		$this->modelFinder = $modelFinder;
		$this->generator = $generator;
		$this->session = $session;
	}

	public function modelSelection()
	{
		// Grab current database tables
		$tables = $this->modelFinder->find('inkit');

		// Build a form with checkboxes for each of them
		$form = $this->backoffice->form(
			action('Digbang\L4Backoffice\Generator\Controllers\GenController@customization'),
			'Choose models',
			'POST'
		);

		foreach ($tables as $table)
		{
			$form->inputs()->checkbox($table->table_name, \Str::titleFromSlug($table->table_name));
		}

		// Return it
		return \View::make('l4-backoffice::gen.model-selection', ['form' => $form]);
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

		$form->inputs()->text('namespace', 'Namespace');

		// Return it
		return \View::make('l4-backoffice::gen.customization', ['form' => $form]);
	}

	public function generation()
	{
		// Iterate over each model
		$tables = $this->session->get('backoffice.gen.tables');

		$backofficeNamespace = 'InkIt\UserInterface\Backoffice\Controllers';
		$entityNamespace = '\InkIt\Domain\Entities\\';

		$fileDir = app_path() . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $backofficeNamespace);
		$templatePath = realpath(
			dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'controller.txt'
		);

		foreach ($tables as $tableName => $on)
		{
			$className = \Str::studly(\Str::singular($tableName));
			$pluralClassname = \Str::plural($className);

			$columns = $this->filterColumns(
				$this->modelFinder->columns($tableName)
			);

			// Generate
			$this->generator->make($templatePath, [
				'namespace' => $backofficeNamespace,
				'classname' => $className,
				'snake_classname' => $tableName,
				'plural_classname' => $pluralClassname,
				'camel_classname' => \Str::camel($tableName),
				'full_model' => $entityNamespace . $className,
				'title_attribute_getter' => array_first($columns, function($key, $value){ return $value != 'id'; }),
				'inputs_into_columns' => $this->columnsInputs($columns),
				'data_into_columns' => $this->columnsData($columns),
				'columns' => $this->columns($columns),
				'columns_with_labels' => $this->columnsLabel($columns),
				'columns_hide' => in_array('id', $columns) ? "'id'" : '',
				'columns_sortable' => $this->columns($columns),
				'form_inputs' => $this->formInputs($columns),
				'filters' => $this->filters($columns)
			], $fileDir . DIRECTORY_SEPARATOR . $className . 'Controller.php');
		}

		// Return
		return \View::make('l4-backoffice::gen.generation', ['tables' => array_keys($tables)]);
	}

	protected function columnsInputs($columns)
	{
		return array_reduce($columns, function($carry, $column){
			if ($carry)
				$carry .= ',' . PHP_EOL . "\t\t\t";

			$carry .= "'$column' => \\Input::get('$column')";

			return $carry;
		});
	}

	protected function columnsData($columns)
	{
		return array_reduce($columns, function($carry, $column){
			if ($carry)
				$carry .= ',' . PHP_EOL . "\t\t\t";

			$carry .= "'$column' => \$entity->$column";

			return $carry;
		}, '');
	}

	protected function columnsLabel($columns)
	{
		return array_reduce($columns, function($carry, $column){
			if ($carry)
				$carry .= ',' . PHP_EOL . "\t\t\t";

			$carry .= "'$column' => '" . \Str::titleFromSlug($column) . "'";

			return $carry;
		}, '');
	}

	protected function columns($columns)
	{
		return array_reduce($columns, function($carry, $column){
			if ($carry)
				$carry .= ',' . PHP_EOL . "\t\t\t";

			$carry .= "'$column'";

			return $carry;
		}, '');
	}

	protected function formInputs($columns)
	{
		return array_reduce($columns, function($carry, $column){
			if ($carry)
				$carry .= PHP_EOL . "\t\t\t";

			$carry .= "->text('$column', '" . \Str::titleFromSlug($column) . "')";

			return $carry;
		}, '');
	}

	protected function filters($columns)
	{
		return array_reduce($columns, function($carry, $column){
			if ($carry)
				$carry .= PHP_EOL . "\t\t\t";

			$carry .= "->text('$column', '" . \Str::titleFromSlug($column) . "', ['class' => 'form-control'])";

			return $carry;
		}, '');
	}

	protected function filterColumns($columns)
	{
		return array_filter($columns, function($column){
			return
				$column != 'created_at' &&
				$column != 'updated_at' &&
				$column != 'deleted_at';
		});
	}
}