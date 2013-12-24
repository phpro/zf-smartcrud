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
     * @param \Zend\Form\Form $form
     */
    public function it_should_trigger_invalid_update_event($eventManager,$form)
    {
        $form->bind(Argument::any())->shouldBeCalled();
        $form->bindOnValidate()->shouldBeCalled();
        $form->setData(Argument::exact($this->getMockPostData()))->shouldBeCalled()->willReturn($form);
        $form->isValid()->shouldBeCalled()->willreturn(false);
        $this->setForm($form);

        $this->update(1, $this->getMockPostData());
        $eventManager->trigger(Argument::which('getName', CrudEvent::INVALID_UPDATE))->shouldBeCalled();
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

        $this->update(1, $this->getMockPostData());
        $eventManager->trigger(Argument::which('getName', CrudEvent::BEFORE_DATA_VALIDATION))->shouldBeCalled();
    }
    /**
     * @param \Zend\EventManager\EventManager $eventManager
     * @param \Zend\Form\Form $form
     */
    public function it_should_trigger_before_update_event($eventManager, $form)
    {
        $form->bind(Argument::any())->shouldBeCalled();
        $form->bindOnValidate()->shouldBeCalled();
        $form->setData(Argument::exact($this->getMockPostData()))->shouldBeCalled()->willReturn($form);
        $form->isValid()->shouldBeCalled()->willreturn(true);
        $this->setForm($form);

        $this->update(1, $this->getMockPostData());
        $eventManager->trigger(Argument::which('getName', CrudEvent::BEFORE_UPDATE))->shouldBeCalled();
    }

    /**
     * @param \Zend\EventManager\EventManager $eventManager
     * @param \Zend\Form\Form $form
     */
    public function it_should_trigger_after_update_event($eventManager, $form)
    {
        $form->bind(Argument::any())->shouldBeCalled();
        $form->bindOnValidate()->shouldBeCalled();
        $form->setData(Argument::exact($this->getMockPostData()))->shouldBeCalled()->willReturn($form);
        $form->isValid()->shouldBeCalled()->willreturn(true);
        $this->setForm($form);

        $this->update(1, $this->getMockPostData());
        $eventManager->trigger(Argument::which('getName', CrudEvent::AFTER_UPDATE))->shouldBeCalled();
    }

    /**
     * @param \PhproSmartCrud\Gateway\AbstractCrudGateway $gateway
     * @param \StdClass $entityµ
     * @param \Zend\Form\Form $form
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

        $this->update(1, $data);
        $gateway->update(Argument::type('stdClass'), Argument::exact($data))->shouldBeCalled();
    }

    /**
     * @param \PhproSmartCrud\Gateway\AbstractCrudGateway $gateway
     * @param \Zend\Form\Form $form
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
        $this->update(1, $data)->shouldReturn(true);

        $gateway->update($arguments, array())->willReturn(false);
        $this->update(1, $data)->shouldReturn(false);
    }

    protected function getMockPostData()
    {
        return array('property' => 'value');
    }
}
