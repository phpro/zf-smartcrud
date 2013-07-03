<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/veewee/PhproSmartCrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace spec\PhproSmartCrud\Gateway;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class AbstractCrudGatewaySpec
 *
 * @package spec\PhproSmartCrud\Gateway
 */
abstract class AbstractCrudGatewaySpec extends ObjectBehavior
{

    /**
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     */
    public function let($serviceManager)
    {
        $this->setServiceManager($serviceManager);
    }

    /**
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     */
    public function it_should_have_fluent_interfaces($serviceManager)
    {
        $this->setServiceManager($serviceManager)->shouldReturn($this);
    }

    public function it_should_implement_PhproSmartCrud_GatewayInterface()
    {
        $this->shouldImplement('PhproSmartCrud\Gateway\CrudGatewayInterface');
    }

    public function it_should_implement_Zend_ServiceManagerAwareInterface()
    {
        $this->shouldImplement('Zend\ServiceManager\ServiceManagerAwareInterface');
    }

    /**
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     */
    public function it_should_have_a_service_manager($serviceManager)
    {
        $this->setServiceManager($serviceManager);
        $this->getServiceManager()->shouldReturn($serviceManager);
    }





}
