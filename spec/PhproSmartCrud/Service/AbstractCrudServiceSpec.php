<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/veewee/PhproSmartCrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace spec\PhproSmartCrud\Service;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class AbstractCrudActionServiceSpec
 *
 * @package spec\PhproSmartCrud\Service
 */
abstract class AbstractCrudServiceSpec extends ObjectBehavior
{

    /**
     * @param \PhproSmartCrud\Gateway\AbstractCrudGateway $gateway
     * @param \Zend\EventManager\EventManager $eventManager
     * @param \stdClass $entity
     */
    public function let($gateway, $eventManager, $entity)
    {
        $this->setGateway($gateway);
        $this->setEventManager($eventManager);
        $this->setEntity($entity);
        $this->setParameters(array());
    }

    public function it_should_have_fluent_interfaces()
    {
        $dummy = Argument::any();
        $this->setParameters($dummy)->shouldReturn($this);
        $this->setGateway($dummy)->shouldReturn($this);
        $this->setEntity($dummy)->shouldReturn($this);
        $this->setEventManager($dummy)->shouldReturn($this);
    }

    public function it_should_have_parameters()
    {
        $params = array('param1' => 'value1', 'param2' => 'value2');
        $this->setParameters($params);
        $this->getParameters()->shouldReturn($params);
    }

    /**
     * @param \PhproSmartCrud\Gateway\AbstractCrudGateway $gateway
     */
    public function it_should_have_a_gateway($gateway)
    {
        $this->setGateway($gateway);
        $this->getGateway()->shouldReturn($gateway);
    }

    /**
     * @param \stdClass $entity
     */
    public function it_should_have_an_entity($entity)
    {
        $this->setEntity($entity);
        $this->getEntity()->shouldReturn($entity);
    }

    /**
     * @param \Zend\EventManager\EventManager $eventManager
     */
    public function it_should_have_an_event_manager($eventManager)
    {
        $this->setEventManager($eventManager);
        $this->getEventManager()->shouldReturn($eventManager);
    }

    /**
     * @param \stdClass $entity
     */
    public function it_should_create_crud_event($entity)
    {
        $eventName = 'test-event-name';
        $params = array('param1' => 'value1', 'param2' => 'value2');
        $this->setParameters($params);
        $this->setEntity($entity);

        $crudEvent = $this->createEvent($eventName);
        $crudEvent->shouldBeAnInstanceOf('PhproSmartCrud\Event\CrudEvent');
        $crudEvent->getName()->shouldReturn($eventName);
        $crudEvent->getTarget()->shouldReturn($entity);
        $crudEvent->getParams()->shouldReturn($params);
    }

}
