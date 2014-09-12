<?php

namespace spec\Phpro\SmartCrud\Console\Helper;

use Phpro\SmartCrud\Gateway\AbstractGatewayFactory;
use Phpro\SmartCrud\Service\AbstractSmartServiceFactory;

class GatewayListHelperSpec extends AbstractHelperSpec
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Phpro\SmartCrud\Console\Helper\GatewayListHelper');
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
                               AbstractSmartServiceFactory::CONFIG_KEY => array(
                                   AbstractSmartServiceFactory::CONFIG_DEFAULT => array(
                                       AbstractSmartServiceFactory::CONFIG_GATEWAY_KEY => 'default.key',
                                   ),
                               )
                          )
        );
        $this->getDefault()->shouldBe('default.key');
    }
}
