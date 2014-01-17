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
 * Class DeleteServiceSpec
 *
 * @package spec\Phpro\SmartCrud\Service
 */
class DeleteServiceSpec extends AbstractSmartServiceSpec
{

    public function it_is_initializable()
    {
        $this->shouldHaveType('Phpro\SmartCrud\Service\DeleteService');
    }

    public function it_should_extend_Phpro_SmartCrud_AbstractSmartService()
    {
        $this->shouldBeAnInstanceOf('Phpro\SmartCrud\Service\AbstractSmartService');
    }

    /**
     * @param \Zend\EventManager\EventManager $eventManager
     */
    public function it_should_trigger_before_delete_event($eventManager)
    {
        $this->run(1, $this->getMockPostData());
        $eventManager->trigger(Argument::which('getName', CrudEvent::BEFORE_DELETE))->shouldBeCalled();
    }

    /**
     * @param \Zend\EventManager\EventManager $eventManager
     */
    public function it_should_trigger_after_delete_event($eventManager)
    {
        $this->run(1, $this->getMockPostData());
        $eventManager->trigger(Argument::which('getName', CrudEvent::AFTER_DELETE))->shouldBeCalled();
    }

    /**
     * @param \Phpro\SmartCrud\Gateway\CrudGatewayInterface $gateway
     * @param \StdClass                                     $entity
     */
    public function it_should_call_delete_function_on_gateway($gateway, $entity)
    {

        $data = $this->getMockPostData();
        $this->setEntityKey('stdClass');
        $gateway->loadEntity(Argument::exact('stdClass'), Argument::exact(1))->shouldBeCalled();
        $gateway->loadEntity(Argument::exact('stdClass'), Argument::exact(1))->willReturn($entity);
        $gateway->delete(Argument::type('stdClass'), Argument::exact($data))->shouldBeCalled();
        $this->setGateway($gateway);
        $this->run(1, $data);

    }

    /**
     * @param \Phpro\SmartCrud\Gateway\CrudGatewayInterface $gateway
     */
    public function it_should_return_gateway_return_value($gateway)
    {
        $data = $this->getMockPostData();

        $arguments = Argument::cetera();

        $gateway->loadEntity($arguments, Argument::exact(1))->shouldBeCalled();
        $gateway->delete($arguments, 1)->willReturn(true);
        $this->run(1, $data)->shouldReturn(true);

        $gateway->delete($arguments, 1)->willReturn(false);
        $this->run(1, $data)->shouldReturn(false);
    }

    protected function getMockPostData()
    {
        return array('property' => 'value');
    }
}
