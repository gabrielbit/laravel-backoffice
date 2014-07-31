<?php namespace spec\Digbang\L4Backoffice\Inputs;

use Digbang\L4Backoffice\Controls\ControlFactory;
use Digbang\L4Backoffice\Uploads\FileUploadHandler;
use Illuminate\View\Factory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FileSpec extends ObjectBehavior
{
	function let(Factory $viewFactory)
	{
		$controlFactory = new ControlFactory($viewFactory->getWrappedObject());

		$this->beConstructedWith(
			new FileUploadHandler(),
			$controlFactory->make('some-view', 'La Bel', ['da' => 'opt']),
			'fileFileName'
		);
	}

    function it_is_initializable()
    {
        $this->shouldHaveType('Digbang\L4Backoffice\Inputs\File');
    }
}
