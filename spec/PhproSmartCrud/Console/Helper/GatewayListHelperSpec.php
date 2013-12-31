<?php

namespace spec\PhproSmartCrud\Console\Helper;

use PhproSmartCrud\Gateway\AbstractGatewayFactory;
use PhproSmartCrud\Service\AbstractSmartCrudServiceFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Prophecy\Prophet;

class GatewayListHelperSpec extends AbstractHelperSpec
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('PhproSmartCrud\Console\Helper\GatewayListHelper');
    }

    public function it_should_create_gateway_list()
    {
        $this->mockConfig(array(
                AbstractGatewayFactory::FACTORY_NAMESPACE => array(
                    'Gateway1' => array(),
                    'Gateway2' => array(),
                ),
            )
        );

        $list = $this->getList();
        $list->shouldBeArray();
        $list[0]->shouldBe('Gateway1');
        $list[1]->shouldBe('Gateway2');
    }

    public function it_should_know_the_default_gateway()
    {
        $this->mockConfig(array(
                               AbstractSmartCrudServiceFactory::CONFIG_KEY => array(
                                   AbstractSmartCrudServiceFactory::CONFIG_DEFAULT => array(
                                       AbstractSmartCrudServiceFactory::CONFIG_GATEWAY_KEY => 'default.key',
                                   ),
                               )
                          )
        );
        $this->getDefault()->shouldBe('default.key');
    }

}

