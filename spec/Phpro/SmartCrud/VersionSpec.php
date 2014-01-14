<?php

namespace spec\Phpro\SmartCrud;

use PhpSpec\ObjectBehavior;

class VersionSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Phpro\SmartCrud\Version');
    }
}
