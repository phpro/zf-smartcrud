<?php

namespace PhproSmartCrud\Listener;


use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;

abstract class AbstractListener
    implements ListenerAggregateInterface, ServiceManagerAwareInterface
{

    /**
     * @var array
     */
    protected $listeners = array();

    /**
     * @var ServiceManager
     */
    protected $serviceManager;


    /**
     * @inheritDoc
     */
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $listener) {
            $events->detach($listener);
        }
    }

    /**
     * @inheritDoc
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
        return $this;
    }

    /**
     * @return \Zend\ServiceManager\ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

}
