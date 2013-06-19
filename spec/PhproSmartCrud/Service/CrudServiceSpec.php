<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/veewee/PhproSmartCrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace spec\PhproSmartCrud\Service;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class CrudServiceSpec
 *
 * @package spec\PhproSmartCrud\Service
 */
class CrudServiceSpec extends AbstractCrudServiceSpec
{
    /**
     * @param \PhproSmartCrud\Gateway\AbstractCrudGateway $gateway
     * @param \Zend\EventManager\EventManager $eventManager
     * @param \stdClass $entity
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     * @param \Zend\Form\Form $form
     */
    public function let($gateway, $eventManager, $entity, $serviceManager, $form)
    {
        parent::let($gateway, $eventManager, $entity);

        $this->setForm($form);
        $this->setServiceManager($serviceManager);
    }


    function it_is_initializable()
    {
        $this->shouldHaveType('PhproSmartCrud\Service\CrudService');
    }

    public function it_should_extend_PhproSmartCrud_AbstractCrudService()
    {
        $this->shouldBeAnInstanceOf('PhproSmartCrud\Service\AbstractCrudService');
    }

    /**
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     */
    public function it_should_have_fluent_interfaces($serviceManager)
    {
        parent::it_should_have_fluent_interfaces();

        $dummy = Argument::any();
        $this->setServiceManager($serviceManager)->shouldReturn($this);
        $this->setForm($dummy)->shouldReturn($this);
    }

    /**
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     */
    public function it_should_have_a_service_manager($serviceManager)
    {
        $this->setServiceManager($serviceManager);
        $this->getServiceManager()->shouldReturn($serviceManager);
    }

    /**
     * @param \Zend\Form\Form $form
     */
    public function it_should_have_a_form($form)
    {
        $this->setForm($form);
        $this->getForm()->shouldReturn($form);
    }

}
