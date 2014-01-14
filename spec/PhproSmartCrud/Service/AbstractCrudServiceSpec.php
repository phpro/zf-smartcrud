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
use Prophecy\Prophet;

/**
 * Class AbstractCrudActionServiceSpec
 *
 * @package spec\PhproSmartCrud\Service
 */
abstract class AbstractCrudServiceSpec extends ObjectBehavior
{

    /**
     * @param array $paramsData
     *
     * @return \PhproSmartCrud\Service\ParametersService
     */
    protected function mockParams(array $paramsData)
    {
        $prophet = new Prophet();
        /** @var \PhproSmartCrud\Service\ParametersService $params  */
        $params = $prophet->prophesize('PhproSmartCrud\Service\ParametersService');
        $params->fromRoute()->willReturn($paramsData);
        $params->fromPost()->willReturn($paramsData);
        $params->fromQuery()->willReturn($paramsData);
        $params->fromRoute('id', Argument::any())->willReturn(1);

        $this->setParameters($params->reveal());

        return $params;
    }

    /**
     * @param \PhproSmartCrud\Gateway\CrudGatewayInterface $gateway
     * @param \Zend\EventManager\EventManager $eventManager
     * @param \Zend\Form\Form $form
     */
    public function let($gateway, $eventManager, $entity)
    {
        $this->setGateway($gateway);
        $this->setEventManager($eventManager);
        $this->setEntity($entity);
        $this->mockParams(array());
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
        $paramsService = $this->mockParams(array('param1' => 'value1', 'param2' => 'value2'));
        $this->getParameters()->shouldReturn($paramsService);
    }

    /**
     * @param \PhproSmartCrud\Gateway\CrudGatewayInterface $gateway
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
        $crudEvent = $this->createEvent($eventName, $entity);
        $crudEvent->shouldBeAnInstanceOf('PhproSmartCrud\Event\CrudEvent');
        $crudEvent->getName()->shouldReturn($eventName);
        $crudEvent->getTarget()->shouldReturn($entity);
        $crudEvent->getParams()->shouldReturn($this->getParameters());
    }

}
