<?php

namespace spec\PhproSmartCrud\Listener;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class AbstractListenerSpec
 *
 * @package spec\PhproSmartCrud\Listener
 */
abstract class AbstractListenerSpec extends ObjectBehavior
{

    public function it_should_implement_zend_ListenerAggregate()
    {
        $this->shouldImplement('Zend\EventManager\ListenerAggregateInterface');
    }

    public function it_should_implement_zend_ServiceManagerAwareInterface()
    {
        $this->shouldImplement('Zend\ServiceManager\ServiceManagerAwareInterface');
    }

    /**
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     */
    public function it_should_have_a_servicemanager($serviceManager)
    {
        $this->setServiceManager($serviceManager);
        $this->getServiceManager()->shouldReturn($serviceManager);
    }

    /**
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     */
    public function it_should_have_a_fluent_interface($serviceManager)
    {
        $this->setServiceManager($serviceManager)->shouldReturn($this);
    }

}
