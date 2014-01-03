<?php

namespace spec\PhproSmartCrud\Controller;

use PhproSmartCrud\Controller\AbstractCrudControllerFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Prophecy\Prophet;

class AbstractCrudControllerFactorySpec extends ObjectBehavior
{

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @param array $routeParams
     */
    protected function mockConfiguration($serviceLocator, $routeParams = array('action' => 'list'))
    {
        $prophet = new Prophet();
        $controllerKey = 'PhproSmartCrud\Controller\CrudController';
        $serviceKey = 'PhproSmartCrud\Service\AbstractCrudService';

        // Mock config
        $serviceLocator->has('Config')->willReturn(true);
        $serviceLocator->get('Config')->willReturn(array(
            AbstractCrudControllerFactory::FACTORY_NAMESPACE => array(
                'custom-controller' => array(
                    AbstractCrudControllerFactory::CONFIG_CONTROLLER => $controllerKey,
                    AbstractCrudControllerFactory::CONFIG_IDENTIFIER => 'id',
                    AbstractCrudControllerFactory::CONFIG_SMART_SERVICE => $serviceKey,
                ),
                'fault-controller' => array(
                    AbstractCrudControllerFactory::CONFIG_CONTROLLER => 'invalid-controller',
                ),
                'fault-service' => array(
                    AbstractCrudControllerFactory::CONFIG_SMART_SERVICE => 'invalid-service',
                )
            )
        ));


        // Mock controller
        $controller = $prophet->prophesize('\PhproSmartCrud\Controller\CrudController');
        $this->mockControllerManager($serviceLocator, $controllerKey, $controller);

        // Mock route
        $this->mockRouteMatch($serviceLocator, $routeParams);

        // Mock service
        $serviceKey = $serviceKey . '::' . $routeParams['action'];
        $service = $prophet->prophesize('\PhproSmartCrud\Service\AbstractCrudService');
        $serviceLocator->has($serviceKey)->willReturn(true);
        $serviceLocator->get($serviceKey)->willReturn($service);
        $serviceLocator->has('invalid-service::' . $routeParams['action'])->willReturn(false);

        $this->setServiceLocator($serviceLocator);
    }

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @param array $params
     */
    protected function mockRouteMatch($serviceLocator, $params = array())
    {
        $prophet = new Prophet();
        $application = $prophet->prophesize('\Zend\Mvc\Application');
        $mvcEvent = $prophet->prophesize('\Zend\Mvc\MvcEvent');
        $routeMatch = $prophet->prophesize('\Zend\Mvc\Router\RouteMatch');

        $serviceLocator->has('application')->willReturn(true);
        $serviceLocator->get('application')->willReturn($application);

        $application->getMvcEvent()->willReturn($mvcEvent);
        $mvcEvent->getRouteMatch()->willReturn($routeMatch);

        $routeMatch->getParam('action', Argument::any())->willReturn('list');
        if ($params) {
            foreach ($params as $key => $value) {
                $routeMatch->getParam($key, Argument::any())->willReturn($value);
            }
        }

        $this->setServiceLocator($serviceLocator);
    }

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @param string $controllername
     * @param \PhproSmartCrud\Controller\CrudControllerInterface $controller
     */
    protected function mockControllerManager($serviceLocator, $controllername, $controller)
    {
        $prophet = new Prophet();
        $controllerManager = $prophet->prophesize('\Zend\Mvc\Controller\ControllerManager');

        $serviceLocator->has('ControllerLoader')->willReturn(true);
        $serviceLocator->get('ControllerLoader')->willReturn($controllerManager);

        $controllerManager->has($controllername)->willReturn(true);
        $controllerManager->get($controllername)->willReturn($controller);

        $controllerManager->has('invalid-controller')->willReturn(false);

        $this->setServiceLocator($serviceLocator);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('PhproSmartCrud\Controller\AbstractCrudControllerFactory');
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
    public function it_should_have_service_locator($serviceLocator)
    {
        $this->setServiceLocator($serviceLocator);
        $this->getServiceLocator()->shouldReturn($serviceLocator);
    }

    public function it_should_have_default_configuration()
    {
        $config = $this->getDefaultConfiguration();
        $config->shouldBeArray();
        $config[AbstractCrudControllerFactory::CONFIG_CONTROLLER]->shouldBeString();
        $config[AbstractCrudControllerFactory::CONFIG_IDENTIFIER]->shouldBeString();
        $config[AbstractCrudControllerFactory::CONFIG_SMART_SERVICE]->shouldBeString();
    }

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     */
    public function it_should_know_current_routematch($serviceLocator)
    {
        $this->mockRouteMatch($serviceLocator);
        $this->getRouteMatch()->shouldBeAnInstanceOf('\Zend\Mvc\Router\RouteMatch');
    }

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     */
    public function it_should_be_able_to_create_crud_controller_services($serviceLocator)
    {
        $this->mockConfiguration($serviceLocator);
        $name = 'custom-controller';
        $this->canCreateServiceWithName($serviceLocator, $name, $name)->shouldReturn(true);
    }

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     */
    public function it_should_not_be_able_to_create_other_objects($serviceLocator)
    {
        $this->mockConfiguration($serviceLocator);
        $name = 'other-controller';
        $this->canCreateServiceWithName($serviceLocator, $name, $name)->shouldReturn(false);
    }


    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     */
    public function it_should_create_controllers($serviceLocator)
    {
        $this->mockConfiguration($serviceLocator);

        $name = 'custom-controller';
        $controller =$this->createServiceWithName($serviceLocator, $name, $name);

        $controller->shouldBeAnInstanceOf('PhproSmartCrud\Controller\CrudControllerInterface');
    }

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     */
    public function it_should_throw_exception_when_controller_does_not_exist($serviceLocator)
    {
        $this->mockConfiguration($serviceLocator);
        $name = 'fault-controller';

        $this->shouldThrow('PhproSmartCrud\Exception\SmartCrudException')
            ->duringCreateServiceWithName($serviceLocator, $name, $name);
    }

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     */
    public function it_should_throw_exception_when_service_does_not_exist($serviceLocator)
    {
        $this->mockConfiguration($serviceLocator);
        $name = 'fault-service';

        $this->shouldThrow('PhproSmartCrud\Exception\SmartCrudException')
            ->duringCreateServiceWithName($serviceLocator, $name, $name);
    }

}
