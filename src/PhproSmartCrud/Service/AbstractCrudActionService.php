<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/veewee/PhproSmartCrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

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
        $event = new CrudEvent($eventName, $this->getEntity(), $this->getParameters());
        return $event;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->getCrudService()->getParameters();
    }

    /**
     * @return mixed
     */
    public function getEntity()
    {
        return $this->getCrudService()->getEntity();
    }

    /**
     * @return \PhproSmartCrud\Gateway\CrudGatewayInterface
     */
    public function getGateway()
    {
        return $this->getCrudService()->getGateway();
    }

}