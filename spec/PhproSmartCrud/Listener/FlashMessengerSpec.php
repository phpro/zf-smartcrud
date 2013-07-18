<?php

namespace spec\PhproSmartCrud\Listener;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FlashMessengerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('PhproSmartCrud\Listener\FlashMessenger');
    }
}
