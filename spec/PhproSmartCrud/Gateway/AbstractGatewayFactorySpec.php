<?php

namespace spec\PhproSmartCrud\Gateway;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Prophecy\Prophet;

/**
 * Class AbstractGatewayFactorySpec
 *
 * @package spec\PhproSmartCrud\Factory
 *
 * @TODO Check if custom gateway really gets configured.
 *
 *
 */
class AbstractGatewayFactorySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('PhproSmartCrud\Gateway\AbstractGatewayFactory');
    }

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     */
    public function mockConfiguration($serviceLocator)
    {
        $serviceLocator->has('Config')->willReturn(true);
        $serviceLocator->get('Config')->willReturn(array(
            'phpro-smartcrud-gateway' => array(
                'custom-gateway' => array(
                    'type' => 'smartcrud.base.gateway',
                    'options' => array(
                        'object_manager' => 'ObjectManager'
                    )
                ),
                'fault-gateway' => array(
                    'type' => 'fault-gateway',
                ),
            )
        ));

        $prophet = new Prophet();
        $objectManager = $prophet->prophesize('Doctrine\Common\Persistence\ObjectManager');

        $serviceLocator->has('ObjectManager')->willReturn(true);
        $serviceLocator->get('ObjectManager')->willReturn($objectManager);
    }

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @param \PhproSmartCrud\Gateway\CrudGatewayInterface $gateway
     */
    public function mockBaseGateway($serviceLocator, $gateway)
    {
        $serviceLocator->has('smartcrud.base.gateway')->willReturn(true);
        $serviceLocator->get('smartcrud.base.gateway')->willReturn($gateway);
    }

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     */
    public function it_should_be_able_to_create_gateway_services($serviceLocator)
    {

        $this->mockConfiguration($serviceLocator);
        $name = 'custom-gateway';
        $this->canCreateServiceWithName($serviceLocator, $name, $name)->shouldReturn(true);
    }

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     */
    public function it_should_not_be_able_to_create_other_objects($serviceLocator)
    {
        $this->mockConfiguration($serviceLocator);
        $name = 'other-object';
        $this->canCreateServiceWithName($serviceLocator, $name, $name)->shouldReturn(false);
    }

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @param \PhproSmartCrud\Gateway\CrudGatewayInterface $gateway
     */
    public function it_should_create_gateway_services($serviceLocator, $gateway)
    {
        $this->mockConfiguration($serviceLocator);
        $this->mockBaseGateway($serviceLocator, $gateway);

        $name = 'custom-gateway';
        $this->createServiceWithName($serviceLocator, $name, $name)->shouldReturn($gateway);
    }

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @param \PhproSmartCrud\Gateway\CrudGatewayInterface $gateway
     */
    public function it_should_throw_exception_when_base_gateway_does_not_exist($serviceLocator, $gateway)
    {
        $this->mockConfiguration($serviceLocator);
        $this->mockBaseGateway($serviceLocator, $gateway);

        $name = 'fault-gateway';

        $serviceLocator->has($name)->willReturn(false);
        $this->shouldThrow('PhproSmartCrud\Exception\SmartCrudException')
            ->duringCreateServiceWithName($serviceLocator, $name, $name);
    }

}
