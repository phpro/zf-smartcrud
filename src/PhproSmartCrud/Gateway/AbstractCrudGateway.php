<?php
namespace PhproSmartCrud\Gateway;

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;

abstract class AbstractCrudGateway implements CrudGatewayInterface, ServiceManagerAwareInterface
{
    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * @param ServiceManager $serviceManager
     *
     * @return $this
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