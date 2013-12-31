<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/veewee/PhproSmartCrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace spec\PhproSmartCrud\Controller;

use PhproSmartCrud\Router\SmartCrudRouter;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Prophecy\Prophet;
use Zend\View\Model\ViewModel;

/**
 * Class CrudControllerSpec
 *
 * @package spec\PhproSmartCrud\Controller
 */
class CrudControllerSpec extends ObjectBehavior
{

    public function it_is_initializable()
    {
        $this->shouldHaveType('PhproSmartCrud\Controller\CrudController');
    }

    public function it_should_extend_Zend_Controller()
    {
        $this->shouldBeAnInstanceOf('Zend\Mvc\Controller\AbstractActionController');
    }

    public function it_should_have_an_identifier_name()
    {
        $this->setIdentifierName('aName')->shouldReturn($this);
        $this->getIdentifierName()->shouldReturn('aName');
    }

    /**
     * @param \PhproSmartCrud\Service\AbstractCrudService $smartService
     */
    public function it_should_have_an_action_service($smartService)
    {
        $this->setSmartService($smartService)->shouldReturn($this);
        $this->getSmartService()->shouldReturn($smartService);
    }

    /**
     * @param \Zend\Mvc\MvcEvent  $mvcEvent
     */
    public function it_should_throw_exception_on_invalid_route($mvcEvent)
    {
        $mvcEvent->getRouteMatch()->willReturn(null);
        $this->shouldThrow('Zend\Mvc\Exception\DomainException')->duringOnDispatch($mvcEvent);
    }
    /**
     * @param \Zend\Mvc\MvcEvent $mvcEvent
     * @param \Zend\Mvc\Router\Http\RouteMatch $routeMatch
     * @param array $params
     */
    protected function mockRouteMatch($mvcEvent, $routeMatch, $params = array())
    {
        // Configure routematch
        $mvcEvent->getRouteMatch()->willReturn($routeMatch);
        $mvcEvent->setResult(Argument::any())->willReturn ($mvcEvent);
        if(array_key_exists('action',$params)) {
            $action = $params['action'];
            $routeMatch->getParam('action', Argument::any())->willReturn($action);
            if(array_key_exists($action,$params)) {
                $routeMatch->getParam($action, Argument::any())->willReturn($params[$action]);
            }
        } else {
            $routeMatch->getParam('action', Argument::any())->willReturn('index');

        }
        if(array_key_exists('smart-service',$params)) {
            $routeMatch->getParam('smart-service', Argument::any())->willReturn($params['smart-service']);
        } else {
            $routeMatch->getParam('smart-service', Argument::any())->willReturn('PhproSmartCrud\Service\AbstractCrudService');
        }

        if(array_key_exists('identifier-name',$params)) {
            $identifierName = $params['identifier-name'];
            $routeMatch->getParam('identifier-name', Argument::any())->willReturn($identifierName);
            if (array_key_exists($identifierName, $params)) {
                $routeMatch->getParam($identifierName, Argument::any())->willReturn($params[$identifierName]);
            } else {
                $routeMatch->getParam($identifierName, Argument::any())->willReturn(null);
            }
        } else {
            $routeMatch->getParam('identifier-name', Argument::any())->willReturn('id');
            $routeMatch->getParam('id', Argument::any())->willReturn(null);
        }

    }

    /**
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     * @param \PhproSmartCrud\Service\AbstractCrudService $smartService
     */
    protected function mockServiceManager($serviceManager, $smartService, $smartServiceKey)
    {
        $this->setServiceLocator($serviceManager);
        $serviceManager->get(Argument::exact($smartServiceKey))->willReturn($smartService);
        return $serviceManager;
    }

    /**
     * @param \Zend\Mvc\MvcEvent $mvcEvent
     * @param \Zend\Mvc\Router\Http\RouteMatch $routeMatch
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     * @param \PhproSmartCrud\Service\AbstractCrudService $smartService
     */
    public function it_should_configure_the_identifier_name_and_smart_service_on_dispatch($mvcEvent, $routeMatch, $serviceManager, $smartService)
    {
        // Configure routematch
        $this->mockRouteMatch($mvcEvent, $routeMatch, array(
                                                           'action'         => 'index',
                                                           'smart-service'  => 'PhproSmartCrud\Service\AbstractCrudService',
                                                           'identifier-name' => 'idenfifier-id',
                                                      ));
        // Configure service
        $serviceManager = $this->mockServiceManager($serviceManager, $smartService, 'PhproSmartCrud\Service\AbstractCrudService::index');
        $this->onDispatch($mvcEvent);
        $this->getIdentifierName()->shouldBe('idenfifier-id');
        $this->getSmartService()->shouldBe($smartService);
    }

    /**
     * @param \Zend\Http\PhpEnvironment\Request $request
     * @param \Zend\Mvc\MvcEvent $mvcEvent
     * @param \Zend\Mvc\Router\Http\RouteMatch $routeMatch
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     * @param \PhproSmartCrud\Service\CreateService $smartService
     * @param \Zend\Mvc\Controller\Plugin\Params $params
     */
    public function it_should_handle_get_to_a_create_action($request, $mvcEvent, $routeMatch, $serviceManager, $smartService, $params)
    {
        $action = 'create';
        $smartService->run(null,Argument::any())->shouldNotBeCalled();

        // Configure routematch
        $this->mockRouteMatch($mvcEvent, $routeMatch, array(
                                                           'action'         => $action,
                                                           'smart-service'  => 'PhproSmartCrud\Service\AbstractCrudService',
                                                           'id'             => '1'
                                                      ));


        // Configure service
        $serviceManager = $this->mockServiceManager($serviceManager, $smartService, 'PhproSmartCrud\Service\AbstractCrudService::' . $action);

        $this->mockGet($request, $mvcEvent, $smartService,$smartService, $params, $action);
    }

    /**
     * @param \Zend\Http\PhpEnvironment\Request $request
     * @param \Zend\Mvc\MvcEvent $mvcEvent
     * @param \Zend\Mvc\Router\Http\RouteMatch $routeMatch
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     * @param \PhproSmartCrud\Service\CreateService $smartService
     * @param \Zend\Mvc\Controller\PluginManager $pluginManager
     * @param \Zend\Mvc\Controller\Plugin\Redirect $redirectPlugin
     * @param \Zend\Mvc\Controller\Plugin\Params $params
     */
    public function it_should_handle_valid_post_to_create_action($request, $mvcEvent, $routeMatch, $serviceManager, $smartService, $pluginManager, $redirectPlugin, $params)
    {
        $postParameters = array('property' => 'value');
        $action = 'create';
        $smartService->run(null,Argument::exact($postParameters))->shouldBeCalled()->willReturn(true);

        // Configure routematch
        $this->mockRouteMatch($mvcEvent, $routeMatch, array(
                                                           'action'         => $action,
                                                           'smart-service'  => 'PhproSmartCrud\Service\AbstractCrudService'
                                                      ));

        $pluginManager->get(Argument::exact('redirect'), null)->shouldBeCalled()->willReturn($redirectPlugin);
        $redirectPlugin->toRoute(Argument::exact(null), Argument::exact(array('action' => 'index')))->shouldBeCalled();
        $redirectPlugin->toRoute(Argument::exact(null), array('action' => 'index'))->willReturn('mockRedirect');

        // Configure service
        $serviceManager = $this->mockServiceManager($serviceManager, $smartService, 'PhproSmartCrud\Service\AbstractCrudService::' . $action);

        $this->mockValidPost($request, $mvcEvent, $pluginManager, $params, $action, $postParameters);
    }

    /**
     * @param \Zend\Http\PhpEnvironment\Request $request
     * @param \Zend\Mvc\MvcEvent $mvcEvent
     * @param \Zend\Mvc\Router\Http\RouteMatch $routeMatch
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     * @param \PhproSmartCrud\Service\CreateService $smartService
     * @param \Zend\Mvc\Controller\Plugin\Params $params
     */
    public function it_should_handle_invalid_post_to_create_action($request, $mvcEvent, $routeMatch, $serviceManager, $smartService, $params)
    {
        $postParameters = array('property' => 'value');
        $action = 'create';
        $smartService->run(null,Argument::exact($postParameters))->shouldBeCalled()->willReturn(false);

        // Configure routematch
        $this->mockRouteMatch($mvcEvent, $routeMatch, array(
                                                           'action'         => $action,
                                                           'smart-service'  => 'PhproSmartCrud\Service\AbstractCrudService'
                                                      ));


        // Configure service
        $serviceManager = $this->mockServiceManager($serviceManager, $smartService, 'PhproSmartCrud\Service\AbstractCrudService::' . $action);

        $this->mockInvalidPost($request, $mvcEvent, $smartService, $params, $action, $postParameters);
    }

    /**
     * @param \Zend\Http\PhpEnvironment\Request $request
     * @param \Zend\Mvc\MvcEvent $mvcEvent
     * @param \Zend\Mvc\Router\Http\RouteMatch $routeMatch
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     * @param \PhproSmartCrud\Service\UpdateService $smartService
     * @param \Zend\Mvc\Controller\PluginManager $pluginManager
     * @param \Zend\Mvc\Controller\Plugin\Redirect $redirectPlugin
     * @param \Zend\Mvc\Controller\Plugin\Params $params
     */
    public function it_should_handle_a_valid_post_to_an_update_action($request, $mvcEvent, $routeMatch, $serviceManager, $smartService, $pluginManager, $redirectPlugin, $params)
    {
        $postParameters = array('property' => 'value');
        $action = 'update';
        $smartService->run(Argument::any(), Argument::exact($postParameters))->shouldBeCalled()->willReturn(true);

        // Configure routematch
        $this->mockRouteMatch($mvcEvent, $routeMatch, array(
                                                           'action'         => $action,
                                                           'smart-service'  => 'PhproSmartCrud\Service\AbstractCrudService',
                                                           'id'             => '1'
                                                      ));

        $pluginManager->get(Argument::exact('params'), null)->shouldBeCalled()->willReturn($params);
        $params->fromRoute(Argument::exact('id'), null)->shouldBeCalled()->willReturn(1);

        $pluginManager->get(Argument::exact('redirect'), null)->shouldBeCalled()->willReturn($redirectPlugin);
        $redirectPlugin->toRoute(Argument::exact(null), Argument::exact(array('action' => 'view')))->shouldBeCalled();
        $redirectPlugin->toRoute(Argument::exact(null), array('action' => 'view'))->willReturn('mockRedirect');

        // Configure service
        $serviceManager = $this->mockServiceManager($serviceManager, $smartService, 'PhproSmartCrud\Service\AbstractCrudService::' . $action);

        $this->mockValidPost($request, $mvcEvent, $pluginManager, $params, $action, $postParameters);
    }

    /**
     * @param \Zend\Http\PhpEnvironment\Request $request
     * @param \Zend\Mvc\MvcEvent $mvcEvent
     * @param \Zend\Mvc\Router\Http\RouteMatch $routeMatch
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     * @param \PhproSmartCrud\Service\UpdateService $smartService
     * @param \Zend\Mvc\Controller\Plugin\Params $params
     */
    public function it_should_handle_a_get_to_an_update_action($request, $mvcEvent, $routeMatch, $serviceManager, $smartService, $params)
    {
        $action = 'update';
        $smartService->run(Argument::any(), Argument::any())->shouldNotBeCalled();

        // Configure routematch
        $this->mockRouteMatch($mvcEvent, $routeMatch, array(
                                                           'action'         => $action,
                                                           'smart-service'  => 'PhproSmartCrud\Service\AbstractCrudService',
                                                           'id'             => '1'
                                                      ));


        // Configure service
        $serviceManager = $this->mockServiceManager($serviceManager, $smartService, 'PhproSmartCrud\Service\AbstractCrudService::' . $action);

        $this->mockGet($request, $mvcEvent, $smartService,$smartService, $params, $action);
    }

    /**
     * @param \Zend\Http\PhpEnvironment\Request $request
     * @param \Zend\Mvc\MvcEvent $mvcEvent
     * @param \Zend\Mvc\Router\Http\RouteMatch $routeMatch
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     * @param \PhproSmartCrud\Service\UpdateService $smartService
     * @param \Zend\Mvc\Controller\Plugin\Params $params
     */
    public function it_should_handle_invalid_post_to__an_update_action($request, $mvcEvent, $routeMatch, $serviceManager, $smartService, $params)
    {
        $postParameters = array('property' => 'value');
        $action = 'update';
        $smartService->run(Argument::any(), Argument::exact($postParameters))->shouldBeCalled()->willReturn(false);

        // Configure routematch
        $this->mockRouteMatch($mvcEvent, $routeMatch, array(
                                                           'action'         => $action,
                                                           'smart-service'  => 'PhproSmartCrud\Service\AbstractCrudService',
                                                           'id'             => '1'
                                                      ));


        // Configure service
        $serviceManager = $this->mockServiceManager($serviceManager, $smartService, 'PhproSmartCrud\Service\AbstractCrudService::' . $action);

        $this->mockInvalidPost($request, $mvcEvent, $smartService, $params, $action, $postParameters);
    }

    /**
     * @param \Zend\Http\PhpEnvironment\Request $request
     * @param \Zend\Mvc\MvcEvent $mvcEvent
     * @param \Zend\Mvc\Router\Http\RouteMatch $routeMatch
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     * @param \PhproSmartCrud\Service\DeleteService $smartService
     * @param \Zend\Mvc\Controller\Plugin\Params $params
     */
    public function it_should_handle_a_get_to_an_delete_action($request, $mvcEvent, $routeMatch, $serviceManager, $smartService, $params)
    {
        $action = 'update';
        $smartService->run(Argument::any(), Argument::any())->shouldNotBeCalled();

        // Configure routematch
        $this->mockRouteMatch($mvcEvent, $routeMatch, array(
                                                           'action'         => $action,
                                                           'smart-service'  => 'PhproSmartCrud\Service\AbstractCrudService',
                                                           'id'             => '1'
                                                      ));


        // Configure service
        $serviceManager = $this->mockServiceManager($serviceManager, $smartService, 'PhproSmartCrud\Service\AbstractCrudService::' . $action);

        $this->mockGet($request, $mvcEvent, $smartService,$smartService, $params, $action);
    }
    /**
     * @param \Zend\Http\PhpEnvironment\Request $request
     * @param \Zend\Mvc\MvcEvent $mvcEvent
     * @param \Zend\Mvc\Router\Http\RouteMatch $routeMatch
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     * @param \PhproSmartCrud\Service\DeleteService $smartService
     * @param \Zend\Mvc\Controller\PluginManager $pluginManager
     * @param \Zend\Mvc\Controller\Plugin\Redirect $redirectPlugin
     * @param \Zend\Mvc\Controller\Plugin\Params $params
     */
    public function it_should_handle_valid_post_to_a_delete_action($request, $mvcEvent, $routeMatch, $serviceManager, $smartService, $pluginManager, $redirectPlugin, $params)
    {
        $postParameters = array('property' => 'value');
        $action = 'delete';
        $smartService->run(Argument::any(), Argument::exact($postParameters))->shouldBeCalled()->willReturn(true);

        // Configure routematch
        $this->mockRouteMatch($mvcEvent, $routeMatch, array(
                                                           'action'         => $action,
                                                           'smart-service'  => 'PhproSmartCrud\Service\AbstractCrudService',
                                                           'id'             => '1'
                                                      ));

        $pluginManager->get(Argument::exact('params'), null)->shouldBeCalled()->willReturn($params);
        $params->fromRoute(Argument::exact('id'), null)->shouldBeCalled()->willReturn(1);

        $pluginManager->get(Argument::exact('redirect'), null)->shouldBeCalled()->willReturn($redirectPlugin);
        $redirectPlugin->toRoute(Argument::exact(null), Argument::exact(array('action' => 'index')))->shouldBeCalled();
        $redirectPlugin->toRoute(Argument::exact(null), array('action' => 'index'))->willReturn('mockRedirect');

        // Configure service
        $serviceManager = $this->mockServiceManager($serviceManager, $smartService, 'PhproSmartCrud\Service\AbstractCrudService::' . $action);

        $this->mockValidPost($request, $mvcEvent, $pluginManager, $params, $action, $postParameters);
    }
    /**
     * @param \Zend\Http\PhpEnvironment\Request $request
     * @param \Zend\Mvc\MvcEvent $mvcEvent
     * @param \Zend\Mvc\Router\Http\RouteMatch $routeMatch
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     * @param \PhproSmartCrud\Service\DeleteService $smartService
     * @param \Zend\Mvc\Controller\Plugin\Params $params
     */
    public function it_should_handle_invalid_post_to__an_delete_action($request, $mvcEvent, $routeMatch, $serviceManager, $smartService, $params)
    {
        $postParameters = array('property' => 'value');
        $action = 'delete';
        $smartService->run(Argument::any(), Argument::exact($postParameters))->shouldBeCalled()->willReturn(false);

        // Configure routematch
        $this->mockRouteMatch($mvcEvent, $routeMatch, array(
                                                           'action'         => $action,
                                                           'smart-service'  => 'PhproSmartCrud\Service\AbstractCrudService',
                                                           'id'             => '1'
                                                      ));


        // Configure service
        $serviceManager = $this->mockServiceManager($serviceManager, $smartService, 'PhproSmartCrud\Service\AbstractCrudService::' . $action);

        $this->mockInvalidPost($request, $mvcEvent, $smartService, $params, $action, $postParameters);
    }

    private function mockGet($request, $mvcEvent, $params, $smartService, $action)
    {
        /** @var \Zend\Mvc\MvcEvent $mvcEvent  */
        $router = new SmartCrudRouter('test');
        $mvcEvent->getRouter()->willReturn($router);

        $prophet = new Prophet();
        $ouput = $prophet->prophesize('PhproSmartCrud\View\Model\ViewModel');
        $smartService->getOutputModel()->shouldBeCalled()->willReturn($ouput);


        $request->isXmlHttpRequest()->willReturn(false);
        $request->isPost()->willReturn(false);
        $request->getPost()->shouldNotBeCalled();

        $mvcEvent->getRequest()->willReturn($request);
        $mvcEvent->setRequest(Argument::any())->willReturn($mvcEvent);
        $mvcEvent->setResponse(Argument::any())->willReturn($mvcEvent);
        $mvcEvent->setTarget(Argument::any())->willReturn($mvcEvent);
        $mvcEvent->setName(Argument::any())->willReturn($mvcEvent);
        $mvcEvent->stopPropagation(Argument::any())->willReturn($mvcEvent);
        $mvcEvent->propagationIsStopped(Argument::any())->willReturn($mvcEvent);
        $mvcEvent->setResult(Argument::any())->willReturn($mvcEvent);

        $this->setEvent($mvcEvent);

        $this->dispatch($request);
    }

    private function mockValidPost($request, $mvcEvent, $pluginManager, $params, $action, $postParameters)
    {
            /** @var \Zend\Mvc\MvcEvent $mvcEvent  */
        $router = new SmartCrudRouter('test');
        $mvcEvent->getRouter()->willReturn($router);

        $pluginManager->setController(Argument::any())->shouldBeCalled();
        $this->setPluginManager($pluginManager);


        $request->isXmlHttpRequest()->willReturn(false);
        $request->isPost()->willReturn(true);
        $request->getPost()->shouldBeCalled();
        $request->getPost()->willReturn($postParameters);

        $mvcEvent->getRequest()->willReturn($request);
        $mvcEvent->setRequest(Argument::any())->willReturn($mvcEvent);
        $mvcEvent->setResponse(Argument::any())->willReturn($mvcEvent);
        $mvcEvent->setTarget(Argument::any())->willReturn($mvcEvent);
        $mvcEvent->setName(Argument::any())->willReturn($mvcEvent);
        $mvcEvent->stopPropagation(Argument::any())->willReturn($mvcEvent);
        $mvcEvent->propagationIsStopped(Argument::any())->willReturn($mvcEvent);
        $mvcEvent->setResult(Argument::any())->willReturn($mvcEvent);

        $this->setEvent($mvcEvent);

        $this->dispatch($request);
    }

    private function mockInValidPost($request, $mvcEvent, $smartService, $params, $action, $postParameters)
    {
        /** @var \Zend\Mvc\MvcEvent $mvcEvent  */
        $router = new SmartCrudRouter('test');
        $mvcEvent->getRouter()->willReturn($router);

        $prophet = new Prophet();
        $ouput = $prophet->prophesize('PhproSmartCrud\View\Model\ViewModel');
        $smartService->getOutputModel()->shouldBeCalled()->willReturn($ouput);
        $request->isXmlHttpRequest()->willReturn(false);
        $request->isPost()->willReturn(true);
        $request->getPost()->shouldBeCalled()->willReturn($postParameters);

        $mvcEvent->getRequest()->willReturn($request);
        $mvcEvent->setRequest(Argument::any())->willReturn($mvcEvent);
        $mvcEvent->setResponse(Argument::any())->willReturn($mvcEvent);
        $mvcEvent->setTarget(Argument::any())->willReturn($mvcEvent);
        $mvcEvent->setName(Argument::any())->willReturn($mvcEvent);
        $mvcEvent->stopPropagation(Argument::any())->willReturn($mvcEvent);
        $mvcEvent->propagationIsStopped(Argument::any())->willReturn($mvcEvent);
        $mvcEvent->setResult(Argument::any())->willReturn($mvcEvent);

        $this->setEvent($mvcEvent);

        $this->dispatch($request);
    }
}
