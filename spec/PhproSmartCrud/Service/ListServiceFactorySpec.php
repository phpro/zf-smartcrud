<?php

namespace spec\PhproSmartCrud\Service;

use PhpSpec\ObjectBehavior;
use PhproSmartCrud\Service\CreateServiceFactory;
use Prophecy\Argument;
use Prophecy\Prophet;

/**
 * Class ListServiceFactorySpec
 *
 * @package spec\PhproSmartCrud\Service
 */
class ListServiceFactorySpec extends AbstractActionServiceFactorySpec
{

    public function it_is_initializable()
    {
        $this->shouldHaveType('PhproSmartCrud\Service\ListServiceFactory');
    }

    public function it_should_have_a_service_key()
    {
        $this->getServiceKey()->shouldBe('PhproSmartCrud\Service\ListService');
    }

}
