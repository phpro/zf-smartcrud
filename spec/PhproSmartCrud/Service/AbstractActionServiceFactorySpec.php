<?php

namespace spec\PhproSmartCrud\Service;

use PhpSpec\ObjectBehavior;
use PhproSmartCrud\Service\CreateServiceFactory;
use Prophecy\Argument;
use Prophecy\Prophet;

/**
 * Class CreateServiceFactorySpec
 *
 * @package spec\PhproSmartCrud\Service
 */
abstract class AbstractActionServiceFactorySpec extends ObjectBehavior
{

    /**
     * Mock a dummy config object
     *
     * @param bool $hasConfig Makes it possible to disable configuration
     */
    protected function mockServiceLocator($hasConfig = true)
    {
        // Create mock objects
        $prophet = new Prophet();
        $serviceLocator = $prophet->prophesize('\Zend\ServiceManager\ServiceLocatorInterface');
        $crudService = $prophet->prophesize('\PhproSmartCrud\Service\AbstractCrudService');
        $listener = $prophet->prophesize('\Zend\EventManager\ListenerAggregateInterface');
        $gateway = $prophet->prophesize('\PhproSmartCrud\Gateway\AbstractCrudGateway');

        // Configurate configuration
        if ($hasConfig) {
            $this->mockConfiguration($serviceLocator, array(
                    'gateway' => 'service.gateway',
                    'listeners' => array(
                       'service.listener',
                    )
                )
            );
        } else {
            $serviceLocator->get('Config')->willReturn(array());
        }

        // Mock service locator:
        $this->setServiceLocator($serviceLocator->reveal());

        // Mock crud service:
        $serviceLocator->has($this->getServiceKey())->willReturn(true);
        $serviceLocator->get($this->getServiceKey())->willReturn($crudService->reveal());

        // Mock gateway
        $serviceLocator->has('service.gateway')->willReturn(true);
        $serviceLocator->get('service.gateway')->willReturn($gateway->reveal());

        // Mock listener
        $serviceLocator->has('service.listener')->willReturn(true);
        $serviceLocator->get('service.listener')->willReturn($listener->reveal());

        // Mock parameters
        $this->mockParams($serviceLocator, array());
    }

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @param array $paramsData
     *
     * @return \PhproSmartCrud\Service\ParametersService
     */
    protected function mockParams($serviceLocator, array $paramsData)
    {
        $prophet = new Prophet();
        /** @var \PhproSmartCrud\Service\ParametersService $params  */
        $params = $prophet->prophesize('PhproSmartCrud\Service\ParametersService');
        $params->fromRoute()->willReturn($paramsData);
        $params->fromPost()->willReturn($paramsData);
        $params->fromQuery()->willReturn($paramsData);
        $params->fromRoute('id', Argument::any())->willReturn(1);

        $params = $params->reveal();
        $serviceLocator->has('PhproSmartCrud\Service\ParametersService')->willReturn(true);
        $serviceLocator->get('PhproSmartCrud\Service\ParametersService')->willReturn($params);
        return $params;
    }

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @param array $config
     */
    protected function mockConfiguration($serviceLocator, $config)
    {
        $serviceLocator->get('Config')->willReturn(array(
            CreateServiceFactory::CONFIG_KEY => $config
        ));
    }

    public function let()
    {
        $this->mockServiceLocator();
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('PhproSmartCrud\Service\CreateServiceFactory');
    }

    public function it_should_implement_zend_FactoryInterface()
    {
        $this->shouldImplement('Zend\ServiceManager\FactoryInterface');
    }

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @param \PhproSmartCrud\Service\AbstractCrudService $crudService
     */
    public function it_should_have_fluent_interfaces($serviceLocator, $crudService)
    {
        $this->setServiceLocator($serviceLocator)->shouldReturn($this);
    }

    public function it_should_have_config()
    {
        $this->getConfig()->shouldBeArray();
    }

    public function it_should_throw_exception_when_no_config_is_set()
    {
        $this->mockServiceLocator(false);
        $this->shouldThrow('PhproSmartCrud\Exception\SmartCrudException')->duringGetConfig();
    }

    public function it_should_find_config_by_key()
    {
        $this->getConfig('gateway')->shouldBeString();
        $this->getConfig('listeners')->shouldBeArray();
        $this->getConfig('InvalidKey')->shouldBeNull();
    }

    /**
     * @param \PhproSmartCrud\Service\AbstractCrudService $crudService
     */
    public function it_should_configurate_gateway($crudService)
    {
        $crudService->setGateway(Argument::any())->willReturn($crudService);

        $this->configureGateway($crudService);
        $crudService->setGateway(Argument::type('\PhproSmartCrud\Gateway\AbstractCrudGateway'))->shouldBeCalled();
    }

    /**
     * @param \PhproSmartCrud\Service\AbstractCrudService $crudService
     */
    public function it_should_have_a_fluent_interface_after_configuring_gateway($crudService)
    {
        $this->configureGateway($crudService)->shouldReturn($this);
    }

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @param \PhproSmartCrud\Service\AbstractCrudService $crudService
     */
    public function it_should_throw_exception_on_invalid_gateway($serviceLocator, $crudService)
    {
        $this->setServiceLocator($serviceLocator);
        $this->mockConfiguration($serviceLocator, array('gateway' => 'service.gateway'));
        $serviceLocator->has('service.gateway')->willReturn(false);

        $this->shouldThrow('PhproSmartCrud\Exception\SmartCrudException')->duringConfigureGateway($crudService);
    }

    /**
     * @param \PhproSmartCrud\Service\AbstractCrudService $crudService
     * @param \Zend\EventManager\EventManager $eventMananger
     *
     * @TODO Find out how to use Any::type with an interface instead of a class
     */
    public function it_should_configurate_listeners($crudService, $eventMananger)
    {
        $crudService->getEventManager()->willReturn($eventMananger);
        $this->configureListeners($crudService);

        $eventMananger->attach(Argument::any())->shouldBeCalledTimes(1);
    }

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @param \PhproSmartCrud\Service\AbstractCrudService $crudService
     * @param \Zend\EventManager\EventManager $eventManager
     */
    public function it_should_throw_exception_on_invalid_listener($serviceLocator, $crudService, $eventManager)
    {
        $this->setServiceLocator($serviceLocator);
        $this->mockConfiguration($serviceLocator, array('listeners' => array('service.listener')));
        $serviceLocator->has('service.listener')->willReturn(false);

        $crudService->getEventManager()->willReturn($eventManager);

        $this->shouldThrow('PhproSmartCrud\Exception\SmartCrudException')->duringConfigureListeners($crudService);
    }

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @param \PhproSmartCrud\Service\AbstractCrudService $crudService
     */
    public function it_should_not_add_listeners_on_invalid_config($serviceLocator, $crudService)
    {
        $this->setServiceLocator($serviceLocator);

        $this->mockConfiguration($serviceLocator, array('listeners' => null));
        $this->configureListeners($crudService)->shouldReturn($this);

        $this->mockConfiguration($serviceLocator, array('listeners' => array()));
        $this->configureListeners($crudService)->shouldReturn($this);

        $this->mockConfiguration($serviceLocator, array('listeners' => 'key'));
        $this->configureListeners($crudService)->shouldReturn($this);
    }

    /**
     * @param \PhproSmartCrud\Service\AbstractCrudService $crudService
     * @param \Zend\EventManager\EventManager $eventMananger
     *
     */
    public function it_should_have_a_fluent_interface_after_configuring_listeners($crudService, $eventMananger)
    {
        $crudService->getEventManager()->willReturn($eventMananger);
        $this->configureListeners($crudService)->shouldReturn($this);
    }

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @param \PhproSmartCrud\Service\AbstractCrudService $crudService
     */
    public function it_should_configure_smartcrud_parameters($serviceLocator, $crudService)
    {
        $this->mockParams($serviceLocator, array());
        $this->configureParameters($crudService);
        $crudService->setParameters(Argument::type('\PhproSmartCrud\Service\ParametersService'))->shouldBeCalled();
    }

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @param \PhproSmartCrud\Service\AbstractCrudService $crudService
     */
    public function it_should_have_fluent_interfaces_after_configuring_smartcrud_parameters($serviceLocator, $crudService)
    {
        $this->mockParams($serviceLocator, array());
        $this->configureParameters($crudService)->shouldReturn($this);
    }

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @param \PhproSmartCrud\Service\AbstractCrudService $crudService
     * @param \stdClass $dummy
     */
    public function it_should_create_crudservice_object($serviceLocator, $crudService, $dummy)
    {
        $this->mockConfiguration($serviceLocator, array('gateway' => 'service.gateway'));
        $serviceLocator->has($this->getServiceKey())->willReturn(true);
        $serviceLocator->get($this->getServiceKey())->willReturn($crudService);
        $serviceLocator->has('service.gateway')->willReturn(true);
        $serviceLocator->get('service.gateway')->willReturn($dummy);
        $this->mockParams($serviceLocator, array());

        $this->createService($serviceLocator)->shouldReturn($crudService);
    }

}
