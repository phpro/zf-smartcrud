<?php

namespace spec\Phpro\SmartCrud\Console\Helper;

use Prophecy\Prophet;

class ConfigHelperSpec extends AbstractHelperSpec
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Phpro\SmartCrud\Console\Helper\ConfigHelper');
    }

    protected function mockApplicationConfig($configKey, $config)
    {
        // Create mocks
        $prophet = new Prophet();
        $helperSet = $prophet->prophesize('\Symfony\Component\Console\Helper\HelperSet');
        $helper = $prophet->prophesize('\Phpro\SmartCrud\Console\Helper\ServiceManagerHelper');
        $serviceManager = $prophet->prophesize('\Zend\ServiceManager\ServiceManager');

        // Add logic
        $serviceManager->get($configKey)->willReturn($config);
        $helper->getServiceManager()->willReturn($serviceManager);
        $helperSet->get('serviceManager')->willReturn($helper);
        $this->setHelperSet($helperSet);
    }

    public function it_should_load_config_from_servicemanager()
    {
        $config = array('Sample config');
        $this->mockApplicationConfig('Config', $config);
        $this->getConfig()->shouldBe($config);
    }

    public function it_should_load_application_config_from_servicemanager()
    {
        $config = array('Sample application config');
        $this->mockApplicationConfig('ApplicationConfig', $config);
        $this->getApplicationConfig()->shouldBe($config);
    }
}
