<?php namespace spec\Digbang\L4Backoffice\Support;

use Digbang\L4Backoffice\Actions\ActionFactory;
use Digbang\L4Backoffice\Controls\ControlFactory;
use Illuminate\Config\Repository as Config;
use Illuminate\Http\Request;
use Illuminate\Translation\Translator;
use Digbang\Security\Urls\SecureUrl;
use Illuminate\View\Factory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Prophecy\Prophet;

class MenuFactorySpec extends ObjectBehavior
{
	protected $prophet;

	function __construct()
	{
		$this->prophet = new Prophet();
	}

	function it_is_initializable(Translator $translator, SecureUrl $secureUrl, Request $request, Factory $viewFactory)
	{
		$this->mockWith($this->aValidConfig(), $translator, $secureUrl, $request, $viewFactory);

		$this->shouldHaveType('Digbang\L4Backoffice\Support\MenuFactory');
	}

	public function it_should_make_a_menu_based_on_a_config_file(Translator $translator, SecureUrl $secureUrl, Request $request, Factory $viewFactory)
	{
		$this->mockWith($this->aValidConfig(), $translator, $secureUrl, $request, $viewFactory);

		$this->make()->shouldBeAnInstanceOf('Digbang\L4Backoffice\Support\Menu');
	}

	public function it_should_squeak_when_a_config_file_is_invalid(Translator $translator, SecureUrl $secureUrl, Request $request, Factory $viewFactory)
	{
		$this->mockWith($this->anInvalidConfig(), $translator, $secureUrl, $request, $viewFactory);

		$this->shouldThrow('UnexpectedValueException')->duringMake();
	}

	protected function mockWith(Config $config, Translator $translator, SecureUrl $secureUrl, Request $request, Factory $viewFactory)
	{
		$controlFactory = new ControlFactory($viewFactory->getWrappedObject());
		$actionFactory  = new ActionFactory($controlFactory);

		$this->beConstructedWith($actionFactory, $controlFactory, $config, $translator, $secureUrl, $request);
	}

	protected function aValidConfig()
	{
		$config = $this->prophet->prophesize('Illuminate\Config\Repository');

		$config->get('l4-backoffice::menu')->willReturn($this->inkitConfig());

		return $config;
	}

	protected function anInvalidConfig()
	{
		$config = $this->prophet->prophesize('Illuminate\Config\Repository');

		$config->get('l4-backoffice::menu')->willReturn([
			'A Menu item' => [
				'permision' => 'a.valid.permission',
				'ruta' => 'a.valid.route.but.wrong.key'
			]
		]);

		return $config;
	}

	protected function inkitConfig()
	{
		return [
			'Home' => [
				'icon'  => 'home',
				'route' => "backoffice.index"
			],
			'Languages' => [
				'icon' => 'book',
				'children' => [
					'List' => [
						'route' => "backoffice.languages.index"
					],
					'Add new' => [
						'permission' => "backoffice.languages.create",
						'route' => "backoffice.languages.create"
					]
				]
			],
			'Genders' => [
				'icon' => 'columns',
				'children' => [
					'List' => [
						'permission' => "backoffice.genders.index",
						'route' => "backoffice.genders.index"
					],
					'Add new' => [
						'permission' => "backoffice.genders.create",
						'route' => "backoffice.genders.create"
					]
				]
			]
		];
	}
}
