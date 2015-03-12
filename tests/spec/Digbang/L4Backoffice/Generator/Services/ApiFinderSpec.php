<?php namespace spec\Digbang\L4Backoffice\Generator\Services;

use fixtures\apis\MockBackofficeApi;
use fixtures\apis\PoorlyDocumentedBackofficeApi;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class ApiFinderSpec
 *
 * @package spec\Digbang\L4Backoffice\Generator\Services
 * @mixin \Digbang\L4Backoffice\Generator\Services\ApiFinder
 */
class ApiFinderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Digbang\L4Backoffice\Generator\Services\ApiFinder');
    }

	function it_should_find_apis_in_a_given_directory()
	{
		$this->all(realpath(__DIR__ . '/../../../../../fixtures/apis'))
			->shouldReturn([
				MockBackofficeApi::class,
				PoorlyDocumentedBackofficeApi::class
			]);
	}

	function it_should_parse_api_methods()
	{
		$this->parseMethods(MockBackofficeApi::class)
			->shouldReturn([
				'aSearchMethod',
				'aStoreMethod',
				'aFindMethod'
			]);

		$this->parseMethods(PoorlyDocumentedBackofficeApi::class)
			->shouldReturn([
				'foo',
				'fooe',
				'fooi',
				'foou'
			]);
	}

	function it_should_parse_api_method_params()
	{
		$this->getParamsFor(MockBackofficeApi::class, 'aSearchMethod')
			->shouldReturn([
				'firstParam' => 'string',
				'secondParam'=> 'string',
				'typedParam' => 'Collection',
				'optional'   => 'int|null',
			]);

		$this->getParamsFor(PoorlyDocumentedBackofficeApi::class, 'foo')
			->shouldReturn([
				'bar' => 'mixed',
				'baz' => 'mixed',
			]);
	}
}
