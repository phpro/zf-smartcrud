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
 * Class UpdateServiceSpec
 *
 * @package spec\Phpro\SmartCrud\Service
 */
class UpdateServiceSpec extends AbstractSmartServiceSpec
{

    public function it_is_initializable()
    {
        $this->shouldHaveType('Phpro\SmartCrud\Service\UpdateService');
    }

    public function it_should_extend_Phpro_SmartCrud_AbstractSmartService()
    {
        $this->shouldBeAnInstanceOf('Phpro\SmartCrud\Service\AbstractSmartService');
    }

    /**
     * @param \Zend\EventManager\EventManager $eventManager
     * @param \Zend\Form\Form                 $form
     */
    public function it_should_trigger_invalid_update_event($eventManager,$form)
    {
        $form->bind(Argument::any())->shouldBeCalled();
        $form->bindOnValidate()->shouldBeCalled();
        $form->setData(Argument::exact($this->getMockPostData()))->shouldBeCalled()->willReturn($form);
        $form->isValid()->shouldBeCalled()->willreturn(false);
        $this->setForm($form);

        $this->run(1, $this->getMockPostData());
        $eventManager->trigger(Argument::which('getName', CrudEvent::INVALID_UPDATE))->shouldBeCalled();
    }

    /**
     * @param \Zend\EventManager\EventManager $eventManager
     * @param \Zend\Form\Form                 $form
     */
    public function it_should_trigger_before_data_validation_event($eventManager,$form)
    {
        $form->bind(Argument::any())->shouldBeCalled();
        $form->bindOnValidate()->shouldBeCalled();
        $form->setData(Argument::exact($this->getMockPostData()))->shouldBeCalled()->willReturn($form);
        $form->isValid()->shouldBeCalled()->willreturn(true);
        $this->setForm($form);

        $this->run(1, $this->getMockPostData());
        $eventManager->trigger(Argument::which('getName', CrudEvent::BEFORE_DATA_VALIDATION))->shouldBeCalled();
    }
    /**
     * @param \Zend\EventManager\EventManager $eventManager
     * @param \Zend\Form\Form                 $form
     */
    public function it_should_trigger_before_update_event($eventManager, $form)
    {
        $form->bind(Argument::any())->shouldBeCalled();
        $form->bindOnValidate()->shouldBeCalled();
        $form->setData(Argument::exact($this->getMockPostData()))->shouldBeCalled()->willReturn($form);
        $form->isValid()->shouldBeCalled()->willreturn(true);
        $this->setForm($form);

        $this->run(1, $this->getMockPostData());
        $eventManager->trigger(Argument::which('getName', CrudEvent::BEFORE_UPDATE))->shouldBeCalled();
    }

    /**
     * @param \Zend\EventManager\EventManager $eventManager
     * @param \Zend\Form\Form                 $form
     */
    public function it_should_trigger_after_update_event($eventManager, $form)
    {
        $form->bind(Argument::any())->shouldBeCalled();
        $form->bindOnValidate()->shouldBeCalled();
        $form->setData(Argument::exact($this->getMockPostData()))->shouldBeCalled()->willReturn($form);
        $form->isValid()->shouldBeCalled()->willreturn(true);
        $this->setForm($form);

        $this->run(1, $this->getMockPostData());
        $eventManager->trigger(Argument::which('getName', CrudEvent::AFTER_UPDATE))->shouldBeCalled();
    }

    /**
     * @param \Phpro\SmartCrud\Gateway\CrudGatewayInterface $gateway
     * @param \StdClass                                    $entity
     * @param \Zend\Form\Form                              $form
     */
    public function it_should_call_update_function_on_gateway($gateway, $entity, $form)
    {
        $form->bind(Argument::any())->shouldBeCalled();
        $form->bindOnValidate()->shouldBeCalled();
        $form->setData(Argument::exact($this->getMockPostData()))->shouldBeCalled()->willReturn($form);
        $form->isValid()->shouldBeCalled()->willreturn(true);
        $this->setForm($form);

        $data = $this->getMockPostData();
        $this->setEntityKey('stdClass');
        $gateway->loadEntity(Argument::exact('stdClass'), Argument::exact(1))->shouldBeCalled();
        $gateway->loadEntity(Argument::exact('stdClass'), Argument::exact(1))->willReturn($entity);
        $gateway->update(Argument::type('stdClass'), Argument::exact($data))->shouldBeCalled();

        $this->run(1, $data);
        $gateway->update(Argument::type('stdClass'), Argument::exact($data))->shouldBeCalled();
    }

    /**
     * @param \Phpro\SmartCrud\Gateway\CrudGatewayInterface $gateway
     * @param \Zend\Form\Form                              $form
     */
    public function it_should_return_gateway_return_value($gateway, $form)
    {
        $form->bind(Argument::any())->shouldBeCalled();
        $form->bindOnValidate()->shouldBeCalled();
        $form->setData(Argument::exact($this->getMockPostData()))->shouldBeCalled()->willReturn($form);
        $form->isValid()->shouldBeCalled()->willreturn(true);
        $this->setForm($form);

        $data = $this->getMockPostData();

        $arguments = Argument::cetera();
        $gateway->loadEntity($arguments, Argument::exact(1))->shouldBeCalled();
        $gateway->update($arguments, array())->willReturn(true);
        $this->run(1, $data)->shouldReturn(true);

        $gateway->update($arguments, array())->willReturn(false);
        $this->run(1, $data)->shouldReturn(false);
    }

    protected function getMockPostData()
    {
        return array('property' => 'value');
    }
}