<?php namespace spec\Digbang\L4Backoffice\Filters;

use Illuminate\Support\Collection;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class TextSpec
 * @mixin \Digbang\L4Backoffice\Filters\Text
 * @package spec\Digbang\L4Backoffice\Filters
 */
class TextSpec extends ObjectBehavior
{
	function let()
	{
		$this->beConstructedWith('aName');
	}

    function it_is_initializable()
    {
        $this->shouldHaveType('Digbang\L4Backoffice\Filters\Text');
	    $this->shouldHaveType('Illuminate\Support\Contracts\RenderableInterface');
    }

	function it_should_let_me_get_a_specific_option()
	{
		$this->setOptions(['class' => 'form-control', 'id' => 'my_text_box']);

		$this->options('class')->shouldNotBe(null);
	}
}
