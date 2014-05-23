<?php

namespace spec\Digbang\L4Backoffice;

use Digbang\L4Backoffice\Column;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class ColumnCollectionSpec
 * @mixin \Digbang\L4Backoffice\ColumnCollection
 * @package spec\Digbang\L4Backoffice
 */
class ColumnCollectionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Digbang\L4Backoffice\ColumnCollection');
    }

	function it_should_be_a_collection()
	{
		$this->shouldHaveType('Illuminate\\Support\\Collection');
	}

	function it_should_hide_columns()
	{
		$aColumn = new Column('an_id');
		$anotherColumn = new Column('another_id');

		$this->push($aColumn);

		$this->visible()->count()->shouldBe(1);
		$this->hidden()->count()->shouldBe(0);

		$this->push($anotherColumn);

		$this->visible()->count()->shouldBe(2);
		$this->hidden()->count()->shouldBe(0);

		$this->hide($aColumn->getId());

		$this->visible()->count()->shouldBe(1);
		$this->hidden()->count()->shouldBe(1);
	}
}
