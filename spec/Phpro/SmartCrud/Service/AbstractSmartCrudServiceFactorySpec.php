<?php

namespace spec\Phpro\SmartCrud\Service;

use PhpSpec\ObjectBehavior;
use \Phpro\SmartCrud\Service\AbstractSmartCrudServiceFactory;

/**
 * Class AbstractSmartCrudServiceFactorySpec
 *
 * @package spec\Phpro\SmartCrud\Service
 */
class AbstractSmartCrudServiceFactorySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Phpro\SmartCrud\Service\AbstractSmartCrudServiceFactory');
    }

    public function it_should_implement_zend_AbstractFactoryInterface()
    {
        $this->shouldImplement('Zend\ServiceManager\AbstractFactoryInterface');
    }

    public function it_should_implement_zend_ServiceLocatorAwareInterface()
    {
        $this->shouldImplement('Zend\ServiceManager\ServiceLocatorAwareInterface');
    }
    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     */
    public function it_should_have_default_configuration_for_the_create_action($serviceLocator)
    {
        $config = array(
            AbstractSmartCrudServiceFactory::CONFIG_KEY => array(
                'Admin\Service\UserServiceFactory' => array(
                )
            )
        );
        $serviceLocator->get('Config')->shouldBeCalled()->willReturn($config);
        $this->setServiceLocator($serviceLocator);
        $this->getConfig('Admin\Service\UserServiceFactory', 'create')->shouldReturn(
            array(
                 AbstractSmartCrudServiceFactory::CONFIG_GATEWAY_KEY => null,
                 AbstractSmartCrudServiceFactory::CONFIG_ENTITY_CLASS => null,
                 AbstractSmartCrudServiceFactory::CONFIG_FORM_KEY     => null,
                 AbstractSmartCrudServiceFactory::CONFIG_LISTENERS_KEY => array(),
                 AbstractSmartCrudServiceFactory::CONFIG_SERVICE_KEY => '\Phpro\SmartCrud\Service\CreateService'
            )
        );
    }
    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     */
    public function it_loads_the_correct_configuration($serviceLocator)
    {
        $config = array(
            AbstractSmartCrudServiceFactory::CONFIG_KEY => array(
                AbstractSmartCrudServiceFactory::CONFIG_DEFAULT => array(
                    AbstractSmartCrudServiceFactory::CONFIG_GATEWAY_KEY => 'Phpro\SmartCrud\Gateway\DoctrineCrudGateway',
                ),
                'Admin\Service\UserServiceFactory' => array(
                    AbstractSmartCrudServiceFactory::CONFIG_DEFAULT => array(
                        AbstractSmartCrudServiceFactory::CONFIG_ENTITY_CLASS => 'SomeModule\Entity\User',
                        AbstractSmartCrudServiceFactory::CONFIG_FORM_KEY     => 'Admin\Form\UserForm'
                    ),
                    'create' => array(
                        AbstractSmartCrudServiceFactory::CONFIG_SERVICE_KEY => '\Phpro\SmartCrud\Service\CreateService',
                        AbstractSmartCrudServiceFactory::CONFIG_LISTENERS_KEY => array(
                            'Admin\Listener\User'
                        ),
                    ),
                    'update' => array(
                        AbstractSmartCrudServiceFactory::CONFIG_GATEWAY_KEY => 'Phpro\SmartCrud\Gateway\ZendDbCrudGateway',
                        AbstractSmartCrudServiceFactory::CONFIG_SERVICE_KEY => '\Phpro\SmartCrud\Service\UpdateService',
                        AbstractSmartCrudServiceFactory::CONFIG_LISTENERS_KEY => array(
                            'Admin\Listener\UserUpdate'
                        ),
                    )

                )
            )
        );

        $serviceLocator->get('Config')->shouldBeCalled()->willReturn($config);
        $this->setServiceLocator($serviceLocator);
        $this->getConfig('Admin\Service\UserServiceFactory', 'create')->shouldReturn(
            array(
                 AbstractSmartCrudServiceFactory::CONFIG_GATEWAY_KEY => 'Phpro\SmartCrud\Gateway\DoctrineCrudGateway',
                 AbstractSmartCrudServiceFactory::CONFIG_ENTITY_CLASS => 'SomeModule\Entity\User',
                 AbstractSmartCrudServiceFactory::CONFIG_FORM_KEY     => 'Admin\Form\UserForm',
                 AbstractSmartCrudServiceFactory::CONFIG_LISTENERS_KEY => array(
                     'Admin\Listener\User'
                 ),
                 AbstractSmartCrudServiceFactory::CONFIG_SERVICE_KEY => '\Phpro\SmartCrud\Service\CreateService',
            )
        );
        $this->getConfig('Admin\Service\UserServiceFactory', 'update')->shouldReturn(
            array(
                 AbstractSmartCrudServiceFactory::CONFIG_GATEWAY_KEY => 'Phpro\SmartCrud\Gateway\ZendDbCrudGateway',
                 AbstractSmartCrudServiceFactory::CONFIG_ENTITY_CLASS => 'SomeModule\Entity\User',
                 AbstractSmartCrudServiceFactory::CONFIG_FORM_KEY     => 'Admin\Form\UserForm',
                 AbstractSmartCrudServiceFactory::CONFIG_LISTENERS_KEY => array(
                     'Admin\Listener\UserUpdate'
                 ),
                 AbstractSmartCrudServiceFactory::CONFIG_SERVICE_KEY => '\Phpro\SmartCrud\Service\UpdateService',

            )
        );
    }

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     */
    public function it_should_check_if_service_can_be_created($serviceLocator)
    {
        $name = "Create service";
        $requestedName = 'classpath/to/service';
        $config = array(
            AbstractSmartCrudServiceFactory::CONFIG_KEY => array(
                AbstractSmartCrudServiceFactory::CONFIG_DEFAULT => array(
                    AbstractSmartCrudServiceFactory::CONFIG_GATEWAY_KEY => 'Phpro\SmartCrud\Gateway\DoctrineCrudGateway',
                ),
                $requestedName => array(
                    AbstractSmartCrudServiceFactory::CONFIG_DEFAULT => array(
                        AbstractSmartCrudServiceFactory::CONFIG_ENTITY_CLASS => 'SomeModule\Entity\User',
                        AbstractSmartCrudServiceFactory::CONFIG_FORM_KEY     => 'Admin\Form\UserForm'
                    ),
                    'create' => array(
                        AbstractSmartCrudServiceFactory::CONFIG_SERVICE_KEY => '\Phpro\SmartCrud\Service\CreateService',
                        AbstractSmartCrudServiceFactory::CONFIG_LISTENERS_KEY => array(
                            'Admin\Listener\User'
                        ),
                    )
                )
            )
        );
        $serviceLocator->get('Config')->shouldBeCalled()->willReturn($config);
        $this->canCreateServiceWithName($serviceLocator, $name, $requestedName . '::create')->shouldReturn(true);
        $this->canCreateServiceWithName($serviceLocator, $name, 'unknownRequestedName')->shouldReturn(false);
    }

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @param \Phpro\SmartCrud\Service\AbstractCrudService  $smartService
     * @param \Zend\EventManager\EventManager              $eventManager
     * @param \StdClass                                    $listener
     * @param \Phpro\SmartCrud\Gateway\CrudGatewayInterface $crudGateway
     * @param \Zend\Form\Form                              $form
     * @param \Phpro\SmartCrud\Service\ParametersService    $parameterService
     *
     */
    public function it_should_create_a_service_and_inject_the_dependencies(
        $serviceLocator, $smartService, $eventManager, $listener, $crudGateway, $form, $parameterService)
    {

        $name = "Create service";
        $requestedName = 'key_in_configuration';
        $serviceKey    = 'module/service';
        $listenerKey   = 'module/listener/1';
        $gatewayKey    = 'module/gateway';
        $formKey       = 'module/form';
        $parameterServiceKey = 'module/ParameterService';
        $entityClassName = 'module/Entity/ClassName';
        $name = "Create service";
        $requestedName = 'classpath/to/service';
        $config = array(
            AbstractSmartCrudServiceFactory::CONFIG_KEY => array(
                $requestedName => array(
                    AbstractSmartCrudServiceFactory::CONFIG_CREATE => array(
                        AbstractSmartCrudServiceFactory::CONFIG_SERVICE_KEY => $serviceKey,
                        AbstractSmartCrudServiceFactory::CONFIG_ENTITY_CLASS => $entityClassName,
                        AbstractSmartCrudServiceFactory::CONFIG_GATEWAY_KEY => $gatewayKey,
                        AbstractSmartCrudServiceFactory::CONFIG_PARAMETERS_KEY => $parameterServiceKey,
                        AbstractSmartCrudServiceFactory::CONFIG_FORM_KEY  => $formKey,
                        AbstractSmartCrudServiceFactory::CONFIG_LISTENERS_KEY => array(
                            $listenerKey
                        )
                    )
                )
            )
        );

        $serviceLocator->get('Config')->shouldBeCalled()->willReturn($config);
        $serviceLocator->get($serviceKey)->shouldBeCalled()->willReturn($smartService);
        $serviceLocator->get($parameterServiceKey)->shouldBeCalled()->willReturn($parameterService);
        $serviceLocator->get($listenerKey)->shouldBeCalled()->willReturn($listener);
        $serviceLocator->get($gatewayKey)->shouldBeCalled()->willReturn($crudGateway);
        $serviceLocator->get($formKey)->shouldBeCalled()->willReturn($form);

        $smartService->setEntityKey($entityClassName)->shouldBeCalled()->willReturn($smartService);
        $smartService->setParameters($parameterService)->shouldBeCalled()->willReturn($smartService);
        $smartService->setGateway($crudGateway)->shouldBeCalled()->willReturn($smartService);
        $smartService->setForm($form)->shouldBeCalled()->willReturn($smartService);
        $smartService->getEventManager()->shouldBeCalled()->willReturn($eventManager);
        $eventManager->attach($listener)->shouldBeCalled();
        $this->createServiceWithName($serviceLocator, $name, $requestedName . '::' . 'create')->shouldReturn($smartService);

    }

}
