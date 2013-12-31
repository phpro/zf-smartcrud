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
 * Class CreateServiceSpec
 *
 * @package spec\PhproSmartCrud\Service
 */
class CreateServiceSpec extends AbstractCrudServiceSpec
{

    public function it_is_initializable()
    {
        $this->shouldHaveType('PhproSmartCrud\Service\CreateService');
    }

    public function it_should_extend_PhproSmartCrud_AbstractCrudService()
    {
        $this->shouldBeAnInstanceOf('PhproSmartCrud\Service\AbstractCrudService');
    }
    /**
     * @param \Zend\EventManager\EventManager $eventManager
     * @param \Zend\Form\Form $form
     */
    public function it_should_trigger_invalid_create_event($eventManager,$form)
    {
        $form->bind(Argument::any())->shouldBeCalled();
        $form->bindOnValidate()->shouldBeCalled();
        $form->setData(Argument::exact($this->getMockPostData()))->shouldBeCalled()->willReturn($form);
        $form->isValid()->shouldBeCalled()->willreturn(false);
        $this->setForm($form);

        $this->run(null,$this->getMockPostData());
        $eventManager->trigger(Argument::which('getName', CrudEvent::INVALID_CREATE))->shouldBeCalled();
    }

    /**
     * @param \Zend\EventManager\EventManager $eventManager
     * @param \Zend\Form\Form $form
     */
    public function it_should_trigger_before_data_validation_event($eventManager,$form)
    {
        $form->bind(Argument::any())->shouldBeCalled();
        $form->bindOnValidate()->shouldBeCalled();
        $form->setData(Argument::exact($this->getMockPostData()))->shouldBeCalled()->willReturn($form);
        $form->isValid()->shouldBeCalled()->willreturn(true);
        $this->setForm($form);


        $this->run(null,$this->getMockPostData());
        $eventManager->trigger(Argument::which('getName', CrudEvent::BEFORE_DATA_VALIDATION))->shouldBeCalled();
    }
    /**
     * @param \Zend\EventManager\EventManager $eventManager
     * @param \Zend\Form\Form $form
     */
    public function it_should_trigger_before_create_event($eventManager,$form)
    {
        $form->bind(Argument::any())->shouldBeCalled();
        $form->bindOnValidate()->shouldBeCalled();
        $form->setData(Argument::exact($this->getMockPostData()))->shouldBeCalled()->willReturn($form);
        $form->isValid()->shouldBeCalled()->willreturn(true);
        $this->setForm($form);

        $this->run(null,$this->getMockPostData());
        $eventManager->trigger(Argument::which('getName', CrudEvent::BEFORE_CREATE))->shouldBeCalled();
    }

    /**
     * @param \Zend\EventManager\EventManager $eventManager
     * @param \Zend\Form\Form $form
     */
    public function it_should_trigger_after_create_event($eventManager, $form)
    {
        $form->bind(Argument::any())->shouldBeCalled(Argument::any());
        $form->bindOnValidate()->shouldBeCalled();
        $form->setData(Argument::exact($this->getMockPostData()))->shouldBeCalled()->willReturn($form);
        $form->isValid()->shouldBeCalled()->willreturn(true);
        $this->setForm($form);

        $this->run(null,$this->getMockPostData());
        $eventManager->trigger(Argument::which('getName', CrudEvent::AFTER_CREATE))->shouldBeCalled();
    }

    /**
     * @param \PhproSmartCrud\Gateway\AbstractCrudGateway $gateway
     * @param \Zend\Form\Form $form
     */
    public function it_should_call_create_function_on_gateway($gateway, $form)
    {
        $form->bind(Argument::any())->shouldBeCalled(Argument::any());
        $form->bindOnValidate()->shouldBeCalled();
        $form->setData(Argument::exact($this->getMockPostData()))->shouldBeCalled()->willReturn($form);
        $form->isValid()->shouldBeCalled()->willreturn(true);
        $this->setForm($form);

        $data = $this->getMockPostData();
        $this->run(null,$data);
        $gateway->create(Argument::type('stdClass'), Argument::exact($data))->shouldBeCalled();
    }

    /**
     * @param \PhproSmartCrud\Gateway\AbstractCrudGateway $gateway
     * @param \Zend\Form\Form $form
     */
    public function it_should_return_gateway_return_value($gateway, $form)
    {
        $form->bind($this->getEntity())->shouldBeCalled();
        $form->bindOnValidate()->shouldBeCalled();
        $form->setData(Argument::exact($this->getMockPostData()))->willReturn($form);
        $form->isValid()->shouldBeCalled()->willreturn(true);
        $this->setForm($form);

        $arguments = Argument::cetera();
        $data = $this->getMockPostData();
        $gateway->create($this->getEntity(), $data)->willReturn(true);
        $this->run(null,$data)->shouldReturn(true);
    }



    protected function getMockPostData()
    {
        return array('property' => 'value');
    }
}
