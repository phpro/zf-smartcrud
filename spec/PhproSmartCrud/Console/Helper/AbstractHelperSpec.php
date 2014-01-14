<?php

namespace spec\PhproSmartCrud\Console\Helper;

use PhpSpec\ObjectBehavior;
use Prophecy\Prophet;

abstract class AbstractHelperSpec extends ObjectBehavior
{

    public function it_should_extend_from_symfony_helper()
    {
        $this->shouldBeAnInstanceOf('Symfony\Component\Console\Helper\Helper');
    }

    public function it_should_have_a_name()
    {
        $this->getName()->shouldBeString();
    }

    /**
     * @param array $config
     */
    protected function mockConfig($config)
    {
        // Create mocks
        $prophet = new Prophet();
        $helperSet = $prophet->prophesize('\Symfony\Component\Console\Helper\HelperSet');
        $helper = $prophet->prophesize('\PhproSmartCrud\Console\Helper\ConfigHelper');

        // Add logic
        $helper->getConfig()->willReturn($config);
        $helper->getApplicationConfig()->willReturn($config);
        $helperSet->get('Config')->willReturn($helper);
        $this->setHelperSet($helperSet);
    }

}
