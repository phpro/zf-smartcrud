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
     * @param \Phpro\SmartCrud\Gateway\CrudGatewayInterface $gateway
     * @param \Zend\EventManager\EventManager               $eventManager
     * @param \Zend\Form\Form                               $form
     * @param \Phpro\SmartCrud\Service\SmartServiceResult   $result
     */
    public function it_should_handle_invalid_data($gateway, $eventManager, $form, $result)
    {
        $entity = new \StdClass();

        $gateway->loadEntity('entityKey', null)->shouldBecalled()->willReturn(null);

        $this->setEntityKey('entityKey');
        $this->setGateway($gateway);
        $this->setForm($form);

        $this->run(null, $this->getMockPostData())->shouldReturnAnInstanceOf('Phpro\SmartCrud\Service\SmartServiceResult');
        ;
        $eventManager->trigger(Argument::which('getName', CrudEvent::INVALID_DELETE))->shouldBeCalled();
        $eventManager->trigger(Argument::which('getName', CrudEvent::BEFORE_DELETE))->shouldNotBeCalled();
        $eventManager->trigger(Argument::which('getName', CrudEvent::AFTER_DELETE))->shouldNotBeCalled();
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
        $postData = $this->getMockPostData();

        $gateway->loadEntity('entityKey', null)->shouldBecalled()->willReturn($entity);
        $gateway->delete($entity, $postData)->shouldBecalled()->willReturn(true);

        $result->setSuccess(true)->shouldBeCalled();
        $result->setEntity($entity)->shouldBeCalled();

        $this->setEntityKey('entityKey');
        $this->setGateway($gateway);
        $this->setResult($result);
        $this->setForm($form);

        $this->run(null, $this->getMockPostData())->shouldReturn($result);
        ;
        $eventManager->trigger(Argument::which('getName', CrudEvent::INVALID_DELETE))->shouldNotBeCalled();
        $eventManager->trigger(Argument::which('getName', CrudEvent::BEFORE_DELETE))->shouldBeCalled();
        $eventManager->trigger(Argument::which('getName', CrudEvent::AFTER_DELETE))->shouldBeCalled();
    }

    protected function getMockPostData()
    {
        return array('property' => 'value');
    }
}
