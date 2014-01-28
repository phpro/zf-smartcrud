<?php

namespace spec\Phpro\SmartCrud\Exception;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class InvalidArgumentExceptionSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Phpro\SmartCrud\Exception\InvalidArgumentException');
    }

    public function it_should_extend_invalidArgumentException()
    {
        $this->shouldHaveType('\InvalidArgumentException');
    }
}
