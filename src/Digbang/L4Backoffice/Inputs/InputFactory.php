<?php namespace Digbang\L4Backoffice\Inputs;

use Digbang\L4Backoffice\Controls\ControlFactory;
use Digbang\L4Backoffice\Controls\ControlInterface;
use Digbang\L4Backoffice\Support\Collection as DigbangCollection;
use Digbang\L4Backoffice\Uploads\FileUploadHandler;
use Illuminate\Http\Request;

class InputFactory
{
	/**
	 * @var \Digbang\L4Backoffice\Controls\ControlFactory
	 */
	protected $controlFactory;
	protected $request;

	function __construct(ControlFactory $controlFactory, Request $request = null)
	{
		$this->controlFactory = $controlFactory;
		$this->request = $request;
	}

	public function text($name, $label = null, $options = [])
    {
	    return $this->make(
		    'l4-backoffice::inputs.text',
		    $label,
		    $this->buildOptions($options, $label),
		    $name
	    );
    }

	public function dropdown($name, $label = null, $data = [], $options = [])
    {
	    if (isset($options['multiple']))
	    {
		    if (!isset($options['id']))
		    {
			    $options['id'] = trim($name, '[]');
		    }

		    if (! ends_with($name, '[]'))
		    {
			    $name .= '[]';
		    }
	    }

	    return new DropDown(
		    $this->controlFactory->make(
			    'l4-backoffice::inputs.dropdown',
			    $label,
			    $this->buildOptions($options, $label)),
		    $name,
		    $this->request ? $this->request->get($name) : null,
		    $data
	    );
    }

	public function button($name, $label, $options = [])
	{
		if (!isset($options['name']))
		{
			$options['name'] = $name;
		}

		if (!isset($options['id']))
		{
			$options['id'] = $name;
		}

		return $this->make(
			'l4-backoffice::inputs.button',
			$label,
			$options,
			$name
		);
	}

	public function checkbox($name, $label, $options = [])
	{
		return $this->make('l4-backoffice::inputs.checkbox', $label, $options, $name);
	}

	public function integer($name, $label, $options = [])
	{
		return $this->make(
			'l4-backoffice::inputs.number',
			$label,
			$this->buildOptions($options, $label),
			$name
		);
	}

	public function date($name, $label, $options = [])
	{
		$class = array_get($options, 'class', '');

		$options['class'] = trim("$class form-date");

		return $this->make(
			'l4-backoffice::inputs.text',
			$label,
			$this->buildOptions($options, $label),
			$name
		);
	}

	public function datetime($name, $label, $options = [])
	{
		return $this->make(
			'l4-backoffice::inputs.datetime',
			$label,
			$this->buildOptions($options, $label),
			$name
		);
	}

	public function time($name, $label, $options = [])
	{
		return $this->make(
			'l4-backoffice::inputs.time',
			$label,
			$this->buildOptions($options, $label),
			$name
		);
	}

	public function password($name, $label, $options = [])
	{
		return $this->make(
			'l4-backoffice::inputs.password',
			$label,
			$this->buildOptions($options, $label),
			$name
		);
	}

	public function textarea($name, $label = null, $options = [])
	{
		return $this->make(
			'l4-backoffice::inputs.textarea',
			$label,
			$this->buildOptions($options, $label),
			$name
		);
	}

	public function wysiwyg($name, $label = null, $options = [])
	{
		$options['class'] = trim(array_get($options, 'class', '') . " wysiwyg");

		return $this->make(
			'l4-backoffice::inputs.textarea',
			$label,
			$this->buildOptions($options, $label),
			$name
		);
	}

	public function collection()
	{
		return new Collection($this, new DigbangCollection());
	}

	public function composite($name, Collection $collection, $label = '', $options = [])
	{
		return new Composite(
			$name,
			$this->controlFactory->make('l4-backoffice::inputs.composite', $label, $options),
			$collection
		);
	}

	public function hidden($name, $options = [])
	{
		$input = $this->make(
			'l4-backoffice::inputs.hidden',
			$name,
			$options,
			$name
		);

		// By default, hidden inputs are hidden ;-)
		$input->hide();

		return $input;
	}

	public function colorPicker($name, $label, $options = [])
	{
		return $this->make(
			'l4-backoffice::inputs.colorpicker',
			$label,
			$options,
			$name
		);
	}

	public function file($name, $label, $options = [])
	{
		return new File(
			new FileUploadHandler(),
			$this->controlFactory->make(
				'l4-backoffice::inputs.file',
				$label,
				$options
			),
			$name,
			$this->request ? $this->request->get($name) : null
		);
	}

	protected function make($view, $label, $options, $name)
	{
		return new Input(
			$this->controlFactory->make(
				$view,
				$label,
				$options
			),
			$name,
			$this->request ? $this->request->get($name) : null
		);
	}

	protected function buildOptions($options, $label)
	{
		$options = array_add($options, 'class', 'form-control');

		if (!$label)
		{
			return $options;
		}

		return array_add($options, 'placeholder', $label);
	}
}
