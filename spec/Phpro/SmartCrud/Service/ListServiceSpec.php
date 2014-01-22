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
 * Class ListServiceSpec
 *
 * @package spec\Phpro\SmartCrud\Service
 */
class ListServiceSpec extends AbstractSmartServiceSpec
{

    public function it_is_initializable()
    {
        $this->shouldHaveType('Phpro\SmartCrud\Service\ListService');
    }

    public function it_should_extend_Phpro_SmartCrud_AbstractSmartService()
    {
        $this->shouldBeAnInstanceOf('Phpro\SmartCrud\Service\AbstractSmartService');
    }

    /**
     * @param \Phpro\SmartCrud\Gateway\CrudGatewayInterface $gateway
     * @param \Zend\EventManager\EventManager               $eventManager
     * @param \Phpro\SmartCrud\Service\SmartServiceResult   $result
     */
    public function it_should_return_a_result($gateway, $eventManager, $result)
    {
        $getData = array();
        $list = array();
        $gateway->getList('entityKey', $getData)->willReturn($list);

        $result->setSuccess(Argument::any())->shouldBeCalled();
        $result->setForm(Argument::any())->shouldNotBeCalled();
        $result->setList($list)->shouldBeCalled();

        $this->setEntityKey('entityKey');
        $this->setGateway($gateway);
        $this->setResult($result);

        $this->run(Argument::any(), $getData)->shouldReturn($result);;
        $eventManager->trigger(Argument::which('getName', CrudEvent::BEFORE_LIST))->shouldBeCalled();
        $eventManager->trigger(Argument::which('getName', CrudEvent::AFTER_LIST))->shouldBeCalled();
    }

}
