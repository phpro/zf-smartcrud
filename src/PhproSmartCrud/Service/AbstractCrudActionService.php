<?php

namespace PhproSmartCrud\Service;

use PhproSmartCrud\Event\CrudEvent;

/**
 * Class AbstractCrudActionService
 *
 * @package PhproSmartCrud\Service
 */
abstract class AbstractCrudActionService
{
    /**
     * @var CrudService
     */
    protected $crudService;

    /**
     * @param \PhproSmartCrud\Service\CrudService $crudService
     *
     * @return $this
     */
    public function setCrudService($crudService)
    {
        $this->crudService = $crudService;
        return $this;
    }

    /**
     * @return \PhproSmartCrud\Service\CrudService
     */
    public function getCrudService()
    {
        return $this->crudService;
    }

    /**
     * @return \Zend\EventManager\EventManager
     */
    public function getEventManager()
    {
        return $this->getCrudService()->getEventManager();
    }

    /**
     * @param $eventName
     *
     * @return CrudEvent
     */
    public function createEvent($eventName)
    {
        $event = new CrudEvent();
        $event->setName($eventName);
        $event->setTarget($this->getCrudService()->getEntity());
        $event->setParameters($this->getCrudservice()->getParameters());
        return $event;
    }

}