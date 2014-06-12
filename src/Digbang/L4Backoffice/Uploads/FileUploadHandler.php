<?php namespace Digbang\L4Backoffice\Uploads;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploadHandler implements UploadHandlerInterface
{
	/**
	 * Moves an uploaded file to the indicated path.
	 * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
	 * @param $to
	 * @return void
	 * @throws \Illuminate\Filesystem\FileNotFoundException if the original file is not found
	 * @throws \RuntimeException if the upload couldn't be fulfilled
	 */
	public function save(UploadedFile $file, $to)
	{
		$file->move($to);
	}
}