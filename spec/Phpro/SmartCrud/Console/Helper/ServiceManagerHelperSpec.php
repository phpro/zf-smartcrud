<?php

namespace spec\Phpro\SmartCrud\Console\Helper;

class ServiceManagerHelperSpec extends AbstractHelperSpec
{

    /**
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     */
    public function let($serviceManager)
    {
        $this->beConstructedWith($serviceManager);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Phpro\SmartCrud\Console\Helper\ServiceManagerHelper');
    }

    /**
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     */
    public function it_should_contain_service_manager($serviceManager)
    {
        $this->getServiceManager()->shouldBe($serviceManager);
    }
}
