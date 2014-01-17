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
     * @param \Phpro\SmartCrud\Gateway\CrudGatewayInterface $gateway
     * @param \Zend\EventManager\EventManager               $eventManager
     * @param \Zend\Form\Form                               $form
     */
    public function it_should_handle_invalid_data($gateway, $eventManager,$form)
    {
        $entity = new \StdClass();
        $entity->id = 1;
        $postData = $this->getMockPostData();
        $this->setEntityKey('entityKey');
        $gateway->loadEntity('entityKey', $entity->id)->shouldBecalled()->willReturn($entity);
        $gateway->update($entity, $postData)->shouldNotBeCalled();
        $this->setGateway($gateway);

        $form->hasValidated()->shouldBeCalled()->willreturn(false);
        $form->bind(Argument::any())->shouldBeCalled();
        $form->bindOnValidate()->shouldBeCalled();
        $form->setData(Argument::exact($this->getMockPostData()))->shouldBeCalled()->willReturn($form);
        $form->isValid()->shouldBeCalled()->willreturn(false);

        $this->setForm($form);
        $this->run($entity->id, $this->getMockPostData())->shouldReturn(false);;
        $eventManager->trigger(Argument::which('getName', CrudEvent::BEFORE_DATA_VALIDATION))->shouldBeCalled();
        $eventManager->trigger(Argument::which('getName', CrudEvent::INVALID_UPDATE))->shouldBeCalled();
        $eventManager->trigger(Argument::which('getName', CrudEvent::BEFORE_UPDATE))->shouldNotBeCalled();
        $eventManager->trigger(Argument::which('getName', CrudEvent::AFTER_UPDATE))->shouldNotBeCalled();
    }

    /**
     * @param \Phpro\SmartCrud\Gateway\CrudGatewayInterface $gateway
     * @param \Zend\EventManager\EventManager               $eventManager
     * @param \Zend\Form\Form                               $form
     */
    public function it_should_handle_valid_data($gateway, $eventManager,$form)
    {
        $entity = new \StdClass();
        $entity->id = 1;
        $postData = $this->getMockPostData();
        $this->setEntityKey('entityKey');
        $gateway->loadEntity('entityKey', $entity->id)->shouldBecalled()->willReturn($entity);
        $gateway->update($entity, $postData)->shouldBecalled()->willReturn(true);
        $this->setGateway($gateway);

        $form->hasValidated()->shouldBeCalled()->willreturn(false);
        $form->bind(Argument::any())->shouldBeCalled();
        $form->bindOnValidate()->shouldBeCalled();
        $form->setData(Argument::exact($this->getMockPostData()))->shouldBeCalled()->willReturn($form);
        $form->isValid()->shouldBeCalled()->willreturn(true);
        $this->setForm($form);

        $this->run($entity->id,$this->getMockPostData())->shouldReturn(true);
        $eventManager->trigger(Argument::which('getName', CrudEvent::BEFORE_DATA_VALIDATION))->shouldBeCalled();
        $eventManager->trigger(Argument::which('getName', CrudEvent::INVALID_UPDATE))->shouldNotBeCalled();
        $eventManager->trigger(Argument::which('getName', CrudEvent::BEFORE_UPDATE))->shouldBeCalled();
        $eventManager->trigger(Argument::which('getName', CrudEvent::AFTER_UPDATE))->shouldBeCalled();
    }

    protected function getMockPostData()
    {
        return array('property' => 'value');
    }
}
