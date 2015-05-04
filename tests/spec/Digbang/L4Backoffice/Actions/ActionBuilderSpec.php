<?php namespace spec\Digbang\L4Backoffice\Actions;

use Digbang\L4Backoffice\Actions\ActionFactory;
use Digbang\L4Backoffice\Actions\ActionInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class ActionBuilderSpec
 *
 * @package spec\Digbang\L4Backoffice\Actions
 * @mixin \Digbang\L4Backoffice\Actions\ActionBuilder
 */
class ActionBuilderSpec extends ObjectBehavior
{
	function let(ActionFactory $actionFactory)
	{
		$this->beConstructedWith($actionFactory);
	}

    function it_is_initializable()
    {
        $this->shouldHaveType('Digbang\L4Backoffice\Actions\ActionBuilder');
    }

	function it_should_set_where_it_points()
	{
		$this->to('some/url')->shouldReturn($this);
	}

	function it_should_set_a_label_for_it()
	{
		$this->labeled('Go there, buddy!')->shouldReturn($this);
	}

	function it_should_accumulate_html_attributes()
	{
		$this->add('rel', 'nav')->shouldReturn($this);
	}

	function it_should_allow_callback_while_accumulating_html_attributes()
	{
		$this->add('onsubmit', function($row){
			return 'alert(' . $row['message'] . '); return false;';
		})->shouldReturn($this);
	}

	function it_should_accumulate_html_attributes_magically()
	{
		$this->addClass('form-control')->shouldReturn($this);
		$this->addDataConfirm('really?')->shouldReturn($this);
	}

	function it_should_allow_a_custom_view()
	{
		$this->view('my.custom.view')->shouldReturn($this);
	}

	function it_should_allow_a_custom_icon()
	{
		$this->icon('fa-cogs')->shouldReturn($this);
	}

	function it_should_let_me_build_links(ActionFactory $actionFactory, ActionInterface $action)
	{
		$target = 'some/url';
		$label = 'Go, go, go!';
		$icon = 'arrow';
		$options = [
			'class' => 'form-control',
			'rel' => 'link',
			'data-confirm' => 'Really?'
		];
		$view = 'my_link_view';

		$actionFactory->link($target, $label, $options, $view, $icon)
			->shouldBeCalled()
			->willReturn($action);


		$link = $this
			->to($target)
			->labeled($label)
			->icon($icon)
			->view($view)
			->addClass($options['class'])
			->addRel($options['rel'])
			->addDataConfirm($options['data-confirm'])
			->asLink();

		$link->shouldBe($action);
	}

	function it_should_let_me_build_forms(ActionFactory $actionFactory, ActionInterface $action)
	{
		$target = 'some/url';
		$label = 'Go, go, go!';
		$method = 'PUT';
		$view = 'my_form_view';
		$options = [
			'class' => 'form-control',
			'rel' => 'link',
			'data-confirm' => 'Really?'
		];

		$actionFactory->form($target, $label, $method, $options, $view)
			->shouldBeCalled()
			->willReturn($action);


		$link = $this
			->to($target)
			->labeled($label)
			->view($view)
			->addClass($options['class'])
			->addRel($options['rel'])
			->addDataConfirm($options['data-confirm'])
			->asForm($method);

		$link->shouldBe($action);
	}
}
