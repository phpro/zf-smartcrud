<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/veewee/PhproSmartCrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace spec\PhproSmartCrud\Service;

use PhproSmartCrud\Event\CrudEvent;
use Prophecy\Argument;

/**
 * Class DeleteServiceSpec
 *
 * @package spec\PhproSmartCrud\Service
 */
class DeleteServiceSpec extends AbstractCrudServiceSpec
{

    public function it_is_initializable()
    {
        $this->shouldHaveType('PhproSmartCrud\Service\DeleteService');
    }

    public function it_should_extend_PhproSmartCrud_AbstractCrudService()
    {
        $this->shouldBeAnInstanceOf('PhproSmartCrud\Service\AbstractCrudService');
    }

    /**
     * @param \Zend\EventManager\EventManager $eventManager
     */
    public function it_should_trigger_before_delete_event($eventManager)
    {
        $this->delete(1, $this->getMockPostData());
        $eventManager->trigger(Argument::which('getName', CrudEvent::BEFORE_DELETE))->shouldBeCalled();
    }

    /**
     * @param \Zend\EventManager\EventManager $eventManager
     */
    public function it_should_trigger_after_delete_event($eventManager)
    {
        $this->delete(1, $this->getMockPostData());
        $eventManager->trigger(Argument::which('getName', CrudEvent::AFTER_DELETE))->shouldBeCalled();
    }

    /**
     * @param \PhproSmartCrud\Gateway\AbstractCrudGateway $gateway
     * @param \StdClass $entity
     */
    public function it_should_call_delete_function_on_gateway($gateway, $entity)
    {

        $data = $this->getMockPostData();
        $this->setEntityKey('stdClass');
        $gateway->loadEntity(Argument::exact('stdClass'), Argument::exact(1))->shouldBeCalled();
        $gateway->loadEntity(Argument::exact('stdClass'), Argument::exact(1))->willReturn($entity);
        $gateway->delete(Argument::type('stdClass'), Argument::exact($data))->shouldBeCalled();
        $this->setGateway($gateway);
        $this->delete(1, $data);

    }

    /**
     * @param \PhproSmartCrud\Gateway\AbstractCrudGateway $gateway
     */
    public function it_should_return_gateway_return_value($gateway)
    {
        $data = $this->getMockPostData();

        $arguments = Argument::cetera();

        $gateway->loadEntity($arguments, Argument::exact(1))->shouldBeCalled();
        $gateway->delete($arguments, 1)->willReturn(true);
        $this->delete(1, $data)->shouldReturn(true);

        $gateway->delete($arguments, 1)->willReturn(false);
        $this->delete(1, $data)->shouldReturn(false);
    }

    protected function getMockPostData()
    {
        return array('property' => 'value');
    }
}
