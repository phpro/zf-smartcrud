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
        $this->delete();
        $eventManager->trigger(Argument::which('getName', CrudEvent::BEFORE_DELETE))->shouldBeCalled();
    }

    /**
     * @param \Zend\EventManager\EventManager $eventManager
     */
    public function it_should_trigger_after_delete_event($eventManager)
    {
        $this->delete();
        $eventManager->trigger(Argument::which('getName', CrudEvent::AFTER_DELETE))->shouldBeCalled();
    }

    /**
     * @param \PhproSmartCrud\Gateway\CrudGatewayInterface $gateway
     */
    public function it_should_call_delete_function_on_gateway($gateway)
    {
        $this->delete();
        $gateway->delete(Argument::type('stdClass'), 1)->shouldBeCalled();
    }

    /**
     * @param \PhproSmartCrud\Gateway\CrudGatewayInterface $gateway
     */
    public function it_should_return_gateway_return_value($gateway)
    {
        $arguments = Argument::cetera();
        $gateway->delete($arguments, 1)->willReturn(true);
        $this->delete()->shouldReturn(true);

        $gateway->delete($arguments, 1)->willReturn(false);
        $this->delete()->shouldReturn(false);
    }

}
