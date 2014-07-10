<?php namespace spec\Digbang\L4Backoffice\Support;

use Digbang\FontAwesome\FontAwesome;
use Digbang\L4Backoffice\Listings\Column;
use Illuminate\Http\Request;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Digbang\L4Backoffice\Support\LinkMaker;

/**
 * Class LinkMakerSpec
 * @mixin \Digbang\L4Backoffice\Support\LinkMaker
 * @package spec\Digbang\L4Backoffice\Support
 */
class LinkMakerSpec extends ObjectBehavior
{
	protected $aColumnId = 'an_id';

    function it_is_initializable(Request $request, FontAwesome $fontAwesome)
    {
	    $this->beConstructedWith($request, $fontAwesome);

        $this->shouldHaveType('Digbang\L4Backoffice\Support\LinkMaker');
    }

	function it_should_make_a_default_asc_sort_link_given_the_proper_request(Request $request, FontAwesome $fontAwesome, Column $aColumn)
	{
		$this->buildExpectations($request, $fontAwesome, $aColumn);

		$this->sort($aColumn)->shouldContain(LinkMaker::SORT_BY . '=' . $this->aColumnId);
		$this->sort($aColumn)->shouldContain(LinkMaker::SORT_SENSE . '=' . LinkMaker::SORT_SENSE_ASC);
	}

	function it_should_make_a_desc_sort_link_given_the_column_is_sorted_already(Request $request, FontAwesome $fontAwesome, Column $aColumn)
	{
		$this->buildExpectations($request, $fontAwesome, $aColumn, $this->aColumnId, LinkMaker::SORT_SENSE_ASC);

		$this->sort($aColumn)->shouldContain(LinkMaker::SORT_BY . '=' . $this->aColumnId);
		$this->sort($aColumn)->shouldContain(LinkMaker::SORT_SENSE . '=' . LinkMaker::SORT_SENSE_DESC);
	}

	function it_should_make_an_asc_sort_link_given_another_column_is_sorted_already(Request $request, FontAwesome $fontAwesome, Column $aColumn)
	{
		$this->buildExpectations($request, $fontAwesome, $aColumn, 'another_column_id', LinkMaker::SORT_SENSE_ASC);

		$this->sort($aColumn)->shouldContain(LinkMaker::SORT_BY . '=' . $this->aColumnId);
		$this->sort($aColumn)->shouldContain(LinkMaker::SORT_SENSE . '=' . LinkMaker::SORT_SENSE_ASC);
	}

	public function getMatchers()
	{
		return [
			'contain' => function($haystack, $needle){
				return strpos($haystack, $needle) !== false;
			}
		];
	}

	protected function buildExpectations(Request $request, FontAwesome $fontAwesome, Column $aColumn, $sortBy = null, $sortSense = null)
	{
		$fontAwesome->icon(Argument::cetera())->willReturn('<i class="fa fa-icon"></i>');

		$request->url()->willReturn('/foo/bar');

		$this->mockRequest($request, $sortBy, $sortSense);

		$this->beConstructedWith($request, $fontAwesome);

		$this->mockColumn($aColumn);
	}

	protected function mockRequest(Request $request, $sortBy = null, $sortSense = null)
	{
		$input = [];
		if ($sortBy && $sortSense)
		{
			$input = [
				LinkMaker::SORT_BY    => $sortBy,
				LinkMaker::SORT_SENSE => $sortSense
			];
		}

		$request->only([ LinkMaker::SORT_BY, LinkMaker::SORT_SENSE ])->willReturn($input);
		$request->except(             Argument::cetera()            )->willReturn($input);
	}

	protected function mockColumn(Column $aColumn)
	{
		$aColumn->getId()   ->willReturn($this->aColumnId);
		$aColumn->getLabel()->willReturn('A Label for a Column');
	}
}
