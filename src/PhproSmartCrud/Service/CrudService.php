<?php

namespace PhproSmartCrud\Service;

use PhproSmartCrud\Gateway\CrudGatewayInterface;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\Form\Form;

/**
 * Class CrudService
 *
 * @package PhproSmartCrud\Service
 */
class CrudService implements ServiceManagerAwareInterface
{

    /**
     * @var array
     */
    protected $parameters;

    /**
     * @var CrudGatewayInterface
     */
    protected $gateway;

    /**
     * @var mixed
     */
    protected $entity;

    /**
     * @var \Zend\EventManager\Event
     */
    protected $eventManager;

    /**
     * @var \Zend\ServiceManager\ServiceManager
     */
    protected $serviceManager;

    /**
     * @var Form
     */
    protected $form;

    /**
     * @return array|\Traversable
     */
    public function getList()
    {
        $service = $this->getServiceManager()->get('PhproSmartCrud\Service\ListService');
        return $service->getList();
    }

    /**
     * @return bool
     */
    public function create()
    {
        if (!$this->getForm()->isValid()) {
            return false;
        }

        $service = $this->getServiceManager()->get('PhproSmartCrud\Service\CreateService');
        return $service->create();
    }

    /**
     * @return mixed
     */
    public function read()
    {
        $service = $this->getServiceManager()->get('PhproSmartCrud\Service\ReadService');
        return $service->read();
    }

    /**
     * @return bool
     */
    public function update()
    {
        if (!$this->getForm()->isValid()) {
            return false;
        }

        $service = $this->getServiceManager()->get('PhproSmartCrud\Service\UpdateService');
        return $service->update();
    }

    /**
     * @return bool
     */
    public function delete()
    {
        $service = $this->getServiceManager()->get('PhproSmartCrud\Service\DeleteService');
        return $service->delete();
    }

    /**
     * @param mixed $entity
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    /**
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param $eventManager
     *
     * @return $this
     */
    public function setEventManager($eventManager)
    {
        $this->eventManager = $eventManager;
        return $this;
    }

    /**
     * @return \Zend\EventManager\Event
     */
    public function getEventManager()
    {
        return $this->eventManager;
    }

    /**
     * @param $gateway
     *
     * @return $this
     */
    public function setGateway($gateway)
    {
        $this->gateway = $gateway;
        return $this;
    }

    /**
     * @return \PhproSmartCrud\Gateway\CrudGatewayInterface
     */
    public function getGateway()
    {
        return $this->gateway;
    }

    /**
     * @param $parameters
     *
     * @return $this
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;
        return $this;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param $serviceManager
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

    /**
     * @param \Zend\Form\Form $form
     *
     * @return $this
     */
    public function setForm($form)
    {
        $this->form = $form;
        return $this;
    }

    /**
     * @return \Zend\Form\Form
     */
    public function getForm()
    {
        return $this->form;
    }

}