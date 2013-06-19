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
use PhproSmartCrud\Event\CrudEvent;
use Prophecy\Argument;

/**
 * Class UpdateServiceSpec
 *
 * @package spec\PhproSmartCrud\Service
 */
class UpdateServiceSpec extends AbstractCrudServiceSpec
{

    public function it_is_initializable()
    {
        $this->shouldHaveType('PhproSmartCrud\Service\UpdateService');
    }

    public function it_should_extend_PhproSmartCrud_AbstractCrudService()
    {
        $this->shouldBeAnInstanceOf('PhproSmartCrud\Service\AbstractCrudService');
    }

    /**
     * @param \Zend\EventManager\EventManager $eventManager
     */
    public function it_should_trigger_before_update_event($eventManager)
    {
        $this->update();
        $eventManager->trigger(Argument::which('getName', CrudEvent::BEFORE_UPDATE))->shouldBeCalled();
    }

    /**
     * @param \Zend\EventManager\EventManager $eventManager
     */
    public function it_should_trigger_after_update_event($eventManager)
    {
        $this->update();
        $eventManager->trigger(Argument::which('getName', CrudEvent::AFTER_UPDATE))->shouldBeCalled();
    }

    /**
     * @param \PhproSmartCrud\Gateway\AbstractCrudGateway $gateway
     */
    public function it_should_call_update_function_on_gateway($gateway)
    {
        $this->update();
        $gateway->update(Argument::type('stdClass'), Argument::exact(array()))->shouldBeCalled();
    }

    /**
     * @param \PhproSmartCrud\Gateway\AbstractCrudGateway $gateway
     */
    public function it_should_return_gateway_return_value($gateway)
    {
        $dummy = Argument::any();

        $gateway->update($dummy, $dummy)->willReturn(true);
        $this->update()->shouldReturn(true);

        $gateway->update($dummy, $dummy)->willReturn(false);
        $this->update()->shouldReturn(false);
    }

}
