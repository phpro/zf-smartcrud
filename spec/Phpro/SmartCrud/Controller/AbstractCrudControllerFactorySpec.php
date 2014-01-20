<?php

namespace spec\Phpro\SmartCrud\Controller;

use Phpro\SmartCrud\Controller\AbstractCrudControllerFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Prophecy\Prophet;

class AbstractCrudControllerFactorySpec extends ObjectBehavior
{

    /**
     * @param \Zend\Mvc\Controller\ControllerManager       $controllerManager
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @param array                                        $routeParams
     */
    protected function mockConfiguration($controllerManager, $serviceLocator, $routeParams = array('action' => 'list'))
    {
        $prophet = new Prophet();
        $controllerKey = 'Phpro\SmartCrud\Controller\CrudController';
        $serviceKey = 'Phpro\SmartCrud\Service\AbstractSmartService';
        $viewBuilderKey = 'Phpro\SmartCrud\View\Model\ViewModelBuilder';
        $controllerManager->getServiceLocator()->willReturn($serviceLocator);

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
        $controller = $prophet->prophesize('\Phpro\SmartCrud\Controller\CrudController');
        $controllerManager->has($controllerKey)->willReturn(true);
        $controllerManager->get($controllerKey)->willReturn($controller);
        $controllerManager->has('invalid-controller')->willReturn(false);

        // Mock route
        $this->mockRouteMatch($serviceLocator, $routeParams);

        // Mock service
        $serviceKey = $serviceKey . '::' . $routeParams['action'];
        $service = $prophet->prophesize('\Phpro\SmartCrud\Service\AbstractSmartService');
        $serviceLocator->has($serviceKey)->willReturn(true);
        $serviceLocator->get($serviceKey)->willReturn($service);
        $serviceLocator->has('invalid-service::' . $routeParams['action'])->willReturn(false);
        $viewBuilder = $prophet->prophesize('\Phpro\SmartCrud\View\Model\ViewModelBuilder');
        $serviceLocator->has($viewBuilderKey)->willReturn(true);
        $serviceLocator->get($viewBuilderKey)->willReturn($viewBuilder);
        $this->setServiceLocator($serviceLocator);
    }

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @param array                                        $params
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

    public function it_is_initializable()
    {
        $this->shouldHaveType('Phpro\SmartCrud\Controller\AbstractCrudControllerFactory');
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
        $config[AbstractCrudControllerFactory::CONFIG_VIEW_MODEL_BUILDER]->shouldBeString();
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
     * @param \Zend\Mvc\Controller\ControllerManager       $controllerManager
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     */
    public function it_should_be_able_to_create_crud_controller_services($controllerManager, $serviceLocator)
    {
        $this->mockConfiguration($controllerManager, $serviceLocator);
        $name = 'custom-controller';
        $this->canCreateServiceWithName($controllerManager, $name, $name)->shouldReturn(true);
    }

    /**
     * @param \Zend\Mvc\Controller\ControllerManager       $controllerManager
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     */
    public function it_should_not_be_able_to_create_other_objects($controllerManager, $serviceLocator)
    {
        $this->mockConfiguration($controllerManager, $serviceLocator);
        $name = 'other-controller';
        $this->canCreateServiceWithName($controllerManager, $name, $name)->shouldReturn(false);
    }

    /**
     * @param \Zend\Mvc\Controller\ControllerManager       $controllerManager
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     */
    public function it_should_create_controllers($controllerManager, $serviceLocator)
    {
        $this->mockConfiguration($controllerManager, $serviceLocator);

        $name = 'custom-controller';
        $controller =$this->createServiceWithName($controllerManager, $name, $name);

        $controller->shouldBeAnInstanceOf('Phpro\SmartCrud\Controller\CrudControllerInterface');
    }

    /**
     * @param \Zend\Mvc\Controller\ControllerManager       $controllerManager
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     */
    public function it_should_throw_exception_when_controller_does_not_exist($controllerManager, $serviceLocator)
    {
        $this->mockConfiguration($controllerManager, $serviceLocator);
        $name = 'fault-controller';

        $this->shouldThrow('Phpro\SmartCrud\Exception\SmartCrudException')
            ->duringCreateServiceWithName($controllerManager, $name, $name);
    }

    /**
     * @param \Zend\Mvc\Controller\ControllerManager       $controllerManager
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     */
    public function it_should_throw_exception_when_service_does_not_exist($controllerManager, $serviceLocator)
    {
        $this->mockConfiguration($controllerManager, $serviceLocator);
        $name = 'fault-service';

        $this->shouldThrow('Phpro\SmartCrud\Exception\SmartCrudException')
            ->duringCreateServiceWithName($controllerManager, $name, $name);
    }

}
