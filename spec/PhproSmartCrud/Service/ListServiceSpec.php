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
 * Class ListServiceSpec
 *
 * @package spec\PhproSmartCrud\Service
 */
class ListServiceSpec extends AbstractCrudServiceSpec
{

    public function it_is_initializable()
    {
        $this->shouldHaveType('PhproSmartCrud\Service\ListService');
    }

    public function it_should_extend_PhproSmartCrud_AbstractCrudService()
    {
        $this->shouldBeAnInstanceOf('PhproSmartCrud\Service\AbstractCrudService');
    }

    /**
     * @param \Zend\EventManager\EventManager $eventManager
     */
    public function it_should_trigger_before_list_event($eventManager)
    {
        $this->getList();
        $eventManager->trigger(Argument::which('getName', CrudEvent::BEFORE_LIST))->shouldBeCalled();
    }

    /**
     * @param \Zend\EventManager\EventManager $eventManager
     */
    public function it_should_trigger_after_list_event($eventManager)
    {
        $this->getList();
        $eventManager->trigger(Argument::which('getName', CrudEvent::AFTER_LIST))->shouldBeCalled();
    }

    /**
     * @param \PhproSmartCrud\Gateway\AbstractCrudGateway $gateway
     */
    public function it_should_call_read_function_on_gateway($gateway)
    {
        $this->getList();
        $gateway->getList(Argument::type('stdClass'), Argument::exact(array()))->shouldBeCalled();
    }

    /**
     * @param \PhproSmartCrud\Gateway\AbstractCrudGateway $gateway
     */
    public function it_should_return_gateway_return_value($gateway)
    {
        $arguments = Argument::cetera();
        $data = array(array('record1'), array('record2'));

        $gateway->getList($arguments)->willReturn($data);
        $this->getList()->shouldReturn($data);

        $gateway->getList($arguments)->willReturn(array());
        $this->getList()->shouldReturn(array());
    }

}
