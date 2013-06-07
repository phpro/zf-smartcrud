<?php
namespace PhproSmartCrud\Event;

use PhproSmartCrud\Gateway\CrudGatewayInterface;
use Zend\EventManager\Event;

/**
 * Class CrudEvent
 *
 * @package PhproSmartCrud\Event
 */
class CrudEvent extends Event
{

    const BEFORE_LIST = 'before-list';
    const AFTER_LIST = 'after-list';
    const BEFORE_CREATE = 'before-create';
    const AFTER_CREATE = 'after-create';
    const BEFORE_READ = 'before-read';
    const AFTER_READ = 'after-read';
    const BEFORE_UPDATE = 'before-update';
    const AFTER_UPDATE = 'after-update';
    const BEFORE_DELETE = 'before-delete';
    const AFTER_DELETE = 'after-delete';

    /**
     * @var array
     */
    protected $parameters;

    /**
     * @var mixed
     */
    protected $entity;

    /**
     * @var CrudGatewayInterface
     */
    protected $gateway;

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
     * @param \PhproSmartCrud\Gateway\CrudGatewayInterface $gateway
     */
    public function setGateway($gateway)
    {
        $this->gateway = $gateway;
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
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

}