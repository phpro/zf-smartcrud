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
     * @param \Phpro\SmartCrud\Service\SmartServiceResult   $result
     */
    public function it_should_handle_no_data($gateway, $eventManager, $form, $result)
    {
        $entity = new \StdClass();
        $entity->id = 1;

        $gateway->loadEntity('entityKey', $entity->id)->shouldBecalled()->willReturn($entity);
        $gateway->update(Argument::any(), Argument::any())->shouldNotBeCalled();

        $form->hasValidated()->shouldBeCalled()->willreturn(false);
        $form->bind(Argument::any())->shouldBeCalled();
        $form->bindOnValidate()->shouldBeCalled();
        $form->setData(Argument::any())->shouldNotBeCalled();
        $form->isValid()->shouldNotBeCalled();

        $this->setEntityKey('entityKey');
        $this->setGateway($gateway);
        $this->setForm($form);

        $this->run($entity->id,null)->shouldReturnAnInstanceOf('Phpro\SmartCrud\Service\SmartServiceResult');;
        $eventManager->trigger(Argument::which('getName', CrudEvent::BEFORE_DATA_VALIDATION))->shouldNotBeCalled();
        $eventManager->trigger(Argument::which('getName', CrudEvent::INVALID_UPDATE))->shouldNotBeCalled();
        $eventManager->trigger(Argument::which('getName', CrudEvent::BEFORE_UPDATE))->shouldNotBeCalled();
        $eventManager->trigger(Argument::which('getName', CrudEvent::AFTER_UPDATE))->shouldNotBeCalled();
    }

    /**
     * @param \Phpro\SmartCrud\Gateway\CrudGatewayInterface $gateway
     * @param \Zend\EventManager\EventManager               $eventManager
     * @param \Zend\Form\Form                               $form
     * @param \Phpro\SmartCrud\Service\SmartServiceResult   $result
     */
    public function it_should_handle_invalid_data($gateway, $eventManager,$form, $result)
    {
        $entity = new \StdClass();
        $entity->id = 1;
        $postData = $this->getMockPostData();

        $gateway->loadEntity('entityKey', $entity->id)->shouldBecalled()->willReturn($entity);
        $gateway->update($entity, $postData)->shouldNotBeCalled();

        $form->hasValidated()->shouldBeCalled()->willreturn(false);
        $form->bind(Argument::any())->shouldBeCalled();
        $form->bindOnValidate()->shouldBeCalled();
        $form->setData(Argument::exact($this->getMockPostData()))->shouldBeCalled()->willReturn($form);
        $form->isValid()->shouldBeCalled()->willreturn(false);

        $result->setSuccess(Argument::any())->shouldNotBeCalled();
        $result->setForm($form)->shouldBeCalled();
        $result->setEntity($entity)->shouldBeCalled();

        $this->setEntityKey('entityKey');
        $this->setGateway($gateway);
        $this->setResult($result);
        $this->setForm($form);

        $this->run($entity->id, $this->getMockPostData())->shouldReturn($result);;
        $eventManager->trigger(Argument::which('getName', CrudEvent::BEFORE_DATA_VALIDATION))->shouldBeCalled();
        $eventManager->trigger(Argument::which('getName', CrudEvent::INVALID_UPDATE))->shouldBeCalled();
        $eventManager->trigger(Argument::which('getName', CrudEvent::BEFORE_UPDATE))->shouldNotBeCalled();
        $eventManager->trigger(Argument::which('getName', CrudEvent::AFTER_UPDATE))->shouldNotBeCalled();
    }

    /**
     * @param \Phpro\SmartCrud\Gateway\CrudGatewayInterface $gateway
     * @param \Zend\EventManager\EventManager               $eventManager
     * @param \Zend\Form\Form                               $form
     * @param \Phpro\SmartCrud\Service\SmartServiceResult   $result
     */
    public function it_should_handle_valid_data($gateway, $eventManager, $form, $result)
    {
        $entity = new \StdClass();
        $entity->id = 1;
        $postData = $this->getMockPostData();

        $gateway->loadEntity('entityKey', $entity->id)->shouldBecalled()->willReturn($entity);
        $gateway->update($entity, $postData)->shouldBecalled()->willReturn(true);

        $form->hasValidated()->shouldBeCalled()->willreturn(false);
        $form->bind(Argument::any())->shouldBeCalled();
        $form->bindOnValidate()->shouldBeCalled();
        $form->setData(Argument::exact($this->getMockPostData()))->shouldBeCalled()->willReturn($form);
        $form->isValid()->shouldBeCalled()->willreturn(true);

        $result->setSuccess(true)->shouldBeCalled();
        $result->setForm($form)->shouldBeCalled();
        $result->setEntity($entity)->shouldBeCalled();

        $this->setEntityKey('entityKey');
        $this->setGateway($gateway);
        $this->setForm($form);
        $this->setResult($result);

        $this->run($entity->id,$this->getMockPostData())->shouldReturn($result);

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
