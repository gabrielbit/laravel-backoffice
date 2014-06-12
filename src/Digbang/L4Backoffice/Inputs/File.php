<?php namespace Digbang\L4Backoffice\Inputs;

use Digbang\L4Backoffice\Controls\ControlInterface;
use Digbang\L4Backoffice\Uploads\UploadHandlerInterface;

/**
 * Class File
 * @package Digbang\L4Backoffice\Inputs
 */
class File extends Input implements InputInterface
{
	protected $uploadHandler;

	function __construct(UploadHandlerInterface $uploadHandler, ControlInterface $control, $name, $value = null)
	{
		parent::__construct($control, $name, $value);

		$this->uploadHandler = $uploadHandler;
	}

	public function save($to)
	{
		$file = $this->value();
		if (!$file instanceof \Symfony\Component\HttpFoundation\File\UploadedFile)
		{
			throw new \UnexpectedValueException('Cannot move a file without upload');
		}

		if (!$file->isValid())
		{
			throw new \InvalidArgumentException('File uploaded is invalid');
		}

		$this->uploadHandler->save($file, $to);
	}
} 