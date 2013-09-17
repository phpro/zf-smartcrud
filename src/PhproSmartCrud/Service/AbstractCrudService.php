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
use PhproSmartCrud\Gateway\CrudGatewayInterface;
use Zend\EventManager\EventManager;

/**
 * Class AbstractCrudService
 *
 * @package PhproSmartCrud\Service
 */
abstract class AbstractCrudService
{

    /**
     * @var ParametersService
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
     * @param mixed $entity
     *
     * @return $this
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param \Zend\EventManager\EventManager $eventManager $eventManager
     *
     * @return $this
     */
    public function setEventManager($eventManager)
    {
        $this->eventManager = $eventManager;
        return $this;
    }

    /**
     * @return \Zend\EventManager\EventManager
     */
    public function getEventManager()
    {
        if (!$this->eventManager) {
            $this->eventManager = new EventManager();
        }
        return $this->eventManager;
    }

    /**
     * @param \PhproSmartCrud\Gateway\CrudGatewayInterface $gateway
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
     * @param array $parameters
     *
     * @return ParametersService
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;
        return $this;
    }

    /**
     * @return ParametersService
     */
    public function getParameters()
    {
        return $this->parameters;
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

}
