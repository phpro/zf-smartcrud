<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/phpro/zf-smartcrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace spec\PhproSmartCrud\Service;

use PhproSmartCrud\Event\CrudEvent;
use Prophecy\Argument;

/**
 * Class ReadServiceSpec
 *
 * @package spec\PhproSmartCrud\Service
 */
class ReadServiceSpec extends AbstractCrudServiceSpec
{

    public function it_is_initializable()
    {
        $this->shouldHaveType('PhproSmartCrud\Service\ReadService');
    }

    public function it_should_extend_PhproSmartCrud_AbstractCrudService()
    {
        $this->shouldBeAnInstanceOf('PhproSmartCrud\Service\AbstractCrudService');
    }

    /**
     * @param \Zend\EventManager\EventManager $eventManager
     */
    public function it_should_trigger_before_read_event($eventManager)
    {
        $this->run(1, null);
        $eventManager->trigger(Argument::which('getName', CrudEvent::BEFORE_READ))->shouldBeCalled();
    }

    /**
     * @param \Zend\EventManager\EventManager $eventManager
     */
    public function it_should_trigger_after_read_event($eventManager)
    {
        $this->run(1, null);
        $eventManager->trigger(Argument::which('getName', CrudEvent::AFTER_READ))->shouldBeCalled();
    }

    /**
     * @param \PhproSmartCrud\Gateway\CrudGatewayInterface $gateway
     */
    public function it_should_call_read_function_on_gateway($gateway)
    {
        $this->run(1, null);
        $gateway->read(Argument::type('stdClass'), 1)->shouldBeCalled();
    }

    /**
     * @param \PhproSmartCrud\Gateway\CrudGatewayInterface $gateway
     */
    public function it_should_return_gateway_return_value($gateway)
    {
        $arguments = Argument::cetera();
        $data = array('column1' => 'value1', 'column2' => 'value2');

        $gateway->read($arguments, 1)->willReturn($data);
        $this->run(1, null)->shouldReturn($data);
        $gateway->read($arguments, 1)->willReturn(null);
        $this->run(1, null)->shouldReturn(null);
    }

}
