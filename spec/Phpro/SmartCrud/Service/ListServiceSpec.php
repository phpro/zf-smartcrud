<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/phpro/zf-smartcrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace spec\Phpro\SmartCrud\Service;

use Phpro\SmartCrud\Event\CrudEvent;
use Prophecy\Argument;

/**
 * Class ListServiceSpec
 *
 * @package spec\Phpro\SmartCrud\Service
 */
class ListServiceSpec extends AbstractCrudServiceSpec
{

    public function it_is_initializable()
    {
        $this->shouldHaveType('Phpro\SmartCrud\Service\ListService');
    }

    public function it_should_extend_Phpro_SmartCrud_AbstractCrudService()
    {
        $this->shouldBeAnInstanceOf('Phpro\SmartCrud\Service\AbstractCrudService');
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
     * @param \Phpro\SmartCrud\Gateway\CrudGatewayInterface $gateway
     */
    public function it_should_call_read_function_on_gateway($gateway)
    {
        $this->getList();
        $gateway->getList(Argument::type('stdClass'), Argument::exact(array()))->shouldBeCalled();
    }

    /**
     * @param \Phpro\SmartCrud\Gateway\CrudGatewayInterface $gateway
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
