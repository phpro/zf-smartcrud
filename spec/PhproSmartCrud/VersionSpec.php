<?php

namespace spec\PhproSmartCrud;

use PhpSpec\ObjectBehavior;

class VersionSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('PhproSmartCrud\Version');
    }
}
