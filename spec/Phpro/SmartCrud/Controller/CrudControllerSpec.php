<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/phpro/zf-smartcrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace spec\Phpro\SmartCrud\Controller;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Prophecy\Prophet;

/**
 * Class CrudControllerSpec
 *
 * @package spec\Phpro\SmartCrud\Controller
 */
class CrudControllerSpec extends ObjectBehavior
{

    public function let()
    {
        $this->setIdentifierName('id');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Phpro\SmartCrud\Controller\CrudController');
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
     * @param \Phpro\SmartCrud\Service\AbstractSmartService $smartService
     */
    public function it_should_have_an_action_service($smartService)
    {
        $this->setSmartService($smartService)->shouldReturn($this);
        $this->getSmartService()->shouldReturn($smartService);
    }
    /**
     * @param \Phpro\SmartCrud\View\Model\ViewModelBuilder $viewModelBuilder
     */
    public function it_should_have_a_view_model_builder($viewModelBuilder)
    {
        $this->setViewModelBuilder($viewModelBuilder)->shouldReturn($this);
        $this->getViewModelBuilder()->shouldReturn($viewModelBuilder);
    }

    /**
     * @param \Zend\Http\PhpEnvironment\Request            $request
     * @param \Zend\Mvc\MvcEvent                           $mvcEvent
     * @param \Phpro\SmartCrud\Service\CreateService       $smartService
     * @param \Phpro\SmartCrud\Service\SmartServiceResult  $smartResult
     * @param \Zend\Mvc\Controller\Plugin\Params           $params
     * @param \Phpro\SmartCrud\View\Model\ViewModelBuilder $viewModelBuilder
     */
    public function it_should_handle_get_to_a_create_action($request, $mvcEvent, $smartService, $smartResult, $params, $viewModelBuilder)
    {
        $action = 'create';
        $smartResult->isSuccessFull()->shouldNotBeCalled();
        $smartService->run(Argument::any(), Argument::any())->willReturn($smartResult);
        $this->mockGet($request, $mvcEvent, $params,$smartResult, $smartService, $action, $viewModelBuilder);
    }

    /**
     * @param \Zend\Http\PhpEnvironment\Request      $request
     * @param \Zend\Mvc\MvcEvent                     $mvcEvent
     * @param \Phpro\SmartCrud\Service\CreateService $smartService
     * @param \Phpro\SmartCrud\Service\SmartServiceResult  $smartResult
     * @param \Zend\Mvc\Controller\PluginManager     $pluginManager
     * @param \Zend\Mvc\Controller\Plugin\Redirect   $redirectPlugin
     * @param \Zend\Mvc\Controller\Plugin\Params     $params
     */
    public function it_should_handle_valid_post_to_create_action($request, $mvcEvent, $smartService, $smartResult, $pluginManager, $redirectPlugin, $params)
    {
        $postParameters = array('property' => 'value');
        $action = 'create';
        $smartResult->isSuccessFull()->shouldBeCalled()->willReturn(true);
        $smartService->run(null,Argument::exact($postParameters))->shouldBeCalled()->willReturn($smartResult);
        $pluginManager->get(Argument::exact('redirect'), null)->shouldBeCalled()->willReturn($redirectPlugin);
        $redirectPlugin->toRoute(Argument::exact(null), Argument::exact(array('action' => 'index')))->shouldBeCalled();
        $redirectPlugin->toRoute(Argument::exact(null), array('action' => 'index'))->willReturn('mockRedirect');
        $this->setSmartService($smartService);
        $this->mockValidPost($request, $mvcEvent, $pluginManager, $params, $action, $postParameters);
    }

    /**
     * @param \Zend\Http\PhpEnvironment\Request            $request
     * @param \Zend\Mvc\MvcEvent                           $mvcEvent
     * @param \Phpro\SmartCrud\Service\CreateService       $smartService
     * @param \Phpro\SmartCrud\Service\SmartServiceResult  $smartResult
     * @param \Zend\Mvc\Controller\Plugin\Params           $params
     * @param \Phpro\SmartCrud\View\Model\ViewModelBuilder $viewModelBuilder
     */
    public function it_should_handle_invalid_post_to_create_action($request, $mvcEvent, $smartService, $smartResult, $params, $viewModelBuilder)
    {
        $postParameters = array('property' => 'value');
        $action = 'create';
        $smartResult->isSuccessFull()->shouldBeCalled()->willReturn(false);
        $smartService->run(null,Argument::exact($postParameters))->shouldBeCalled()->willReturn($smartResult);
        $this->mockInvalidPost($request, $mvcEvent, $smartResult, $smartService, $params, $action, $postParameters, $viewModelBuilder);
    }

    /**
     * @param \Zend\Http\PhpEnvironment\Request            $request
     * @param \Zend\Mvc\MvcEvent                           $mvcEvent
     * @param \Phpro\SmartCrud\Service\UpdateService       $smartService
     * @param \Phpro\SmartCrud\Service\SmartServiceResult  $smartResult
     * @param \Zend\Mvc\Controller\Plugin\Params           $params
     * @param \Phpro\SmartCrud\View\Model\ViewModelBuilder $viewModelBuilder
     */
    public function it_should_handle_get_to_a_update_action($request, $mvcEvent, $smartService, $smartResult, $params, $viewModelBuilder)
    {
        $action = 'update';
        $smartResult->isSuccessFull()->shouldNotBeCalled();
        $smartService->run(Argument::any(), Argument::any())->willReturn($smartResult);
        $this->mockGet($request, $mvcEvent, $params,$smartResult, $smartService, $action, $viewModelBuilder);
    }

    /**
     * @param \Zend\Http\PhpEnvironment\Request            $request
     * @param \Zend\Mvc\MvcEvent                           $mvcEvent
     * @param \Phpro\SmartCrud\Service\UpdateService       $smartService
     * @param \Phpro\SmartCrud\Service\SmartServiceResult  $smartResult
     * @param \Zend\Mvc\Controller\Plugin\Params           $params
     * @param \Phpro\SmartCrud\View\Model\ViewModelBuilder $viewModelBuilder
     */
    public function it_should_handle_invalid_post_to_an_update_action($request, $mvcEvent, $smartService, $smartResult, $params, $viewModelBuilder)
    {
        $postParameters = array('property' => 'value');
        $action = 'update';
        $smartResult->isSuccessFull()->shouldBeCalled()->willReturn(false);
        $smartService->run(Argument::any(),Argument::any())->shouldBeCalled()->willReturn($smartResult);
        $this->mockInvalidPost($request, $mvcEvent, $smartResult, $smartService, $params, $action, $postParameters, $viewModelBuilder);
    }

    /**
     * @param \Zend\Http\PhpEnvironment\Request      $request
     * @param \Zend\Mvc\MvcEvent                     $mvcEvent
     * @param \Phpro\SmartCrud\Service\UpdateService $smartService
     * @param \Phpro\SmartCrud\Service\SmartServiceResult  $smartResult
     * @param \Zend\Mvc\Controller\PluginManager     $pluginManager
     * @param \Zend\Mvc\Controller\Plugin\Redirect   $redirectPlugin
     * @param \Zend\Mvc\Controller\Plugin\Params     $params
     */
    public function it_should_handle_a_valid_post_to_an_update_action($request, $mvcEvent, $smartService, $smartResult, $pluginManager, $redirectPlugin, $params)
    {
        $postParameters = array('property' => 'value');
        $action = 'update';
        $smartResult->isSuccessFull()->shouldBeCalled()->willReturn(true);
        $smartService->run(Argument::any(), Argument::exact($postParameters))->shouldBeCalled()->willReturn($smartResult);
        $pluginManager->get(Argument::exact('params'), null)->shouldBeCalled()->willReturn($params);
        $params->fromRoute(Argument::exact('id'), null)->shouldBeCalled()->willReturn(1);
        $pluginManager->get(Argument::exact('redirect'), null)->shouldBeCalled()->willReturn($redirectPlugin);
        $redirectPlugin->toRoute(Argument::exact(null), Argument::exact(array('action' => 'update')))->shouldBeCalled();
        $redirectPlugin->toRoute(Argument::exact(null), array('action' => 'update'))->willReturn('mockRedirect');
        $this->setSmartService($smartService);
        $this->mockValidPost($request, $mvcEvent, $pluginManager, $params, $action, $postParameters);
    }

    /**
     * @param \Zend\Http\PhpEnvironment\Request            $request
     * @param \Zend\Mvc\MvcEvent                           $mvcEvent
     * @param \Phpro\SmartCrud\Service\DeleteService       $smartService
     * @param \Phpro\SmartCrud\Service\SmartServiceResult  $smartResult
     * @param \Zend\Mvc\Controller\Plugin\Params           $params
     * @param \Phpro\SmartCrud\View\Model\ViewModelBuilder $viewModelBuilder
     */
    public function it_should_handle_get_to_a_delete_action($request, $mvcEvent, $smartService, $smartResult, $params, $viewModelBuilder)
    {
        $action = 'delete';
        $smartResult->isSuccessFull()->shouldNotBeCalled();
        $smartService->run(Argument::any(), Argument::any())->willReturn($smartResult);
        $this->mockGet($request, $mvcEvent, $params,$smartResult, $smartService, $action, $viewModelBuilder);
    }

    /**
     * @param \Zend\Http\PhpEnvironment\Request      $request
     * @param \Zend\Mvc\MvcEvent                     $mvcEvent
     * @param \Phpro\SmartCrud\Service\DeleteService $smartService
     * @param \Phpro\SmartCrud\Service\SmartServiceResult  $smartResult
     * @param \Zend\Mvc\Controller\PluginManager     $pluginManager
     * @param \Zend\Mvc\Controller\Plugin\Redirect   $redirectPlugin
     * @param \Zend\Mvc\Controller\Plugin\Params     $params
     */
    public function it_should_handle_valid_post_to_a_delete_action($request, $mvcEvent, $smartService, $smartResult, $pluginManager, $redirectPlugin, $params)
    {
        $postParameters = array('property' => 'value');
        $action = 'delete';
        $smartResult->isSuccessFull()->shouldBeCalled()->willReturn(true);
        $smartService->run(Argument::any(), Argument::exact($postParameters))->shouldBeCalled()->willReturn($smartResult);
        $params->fromRoute(Argument::exact('id'), null)->shouldBeCalled()->willReturn(1);
        $pluginManager->get(Argument::exact('params'), null)->shouldBeCalled()->willReturn($params);
        $pluginManager->get(Argument::exact('redirect'), null)->shouldBeCalled()->willReturn($redirectPlugin);
        $redirectPlugin->toRoute(Argument::exact(null), Argument::exact(array('action' => 'index')))
                       ->shouldBeCalled()
                       ->willReturn('mockRedirect');
        $this->setSmartService($smartService);
        $this->mockValidPost($request, $mvcEvent, $pluginManager, $params, $action, $postParameters);
    }

    /**
     * @param \Zend\Http\PhpEnvironment\Request            $request
     * @param \Zend\Mvc\MvcEvent                           $mvcEvent
     * @param \Phpro\SmartCrud\Service\DeleteService       $smartService
     * @param \Phpro\SmartCrud\Service\SmartServiceResult  $smartResult
     * @param \Zend\Mvc\Controller\Plugin\Params           $params
     * @param \Phpro\SmartCrud\View\Model\ViewModelBuilder $viewModelBuilder
     */
    public function it_should_handle_invalid_post_to_a_delete_action($request, $mvcEvent, $smartService, $smartResult, $params, $viewModelBuilder)
    {
        $postParameters = array('property' => 'value');
        $action = 'delete';
        $smartResult->isSuccessFull()->shouldBeCalled()->willReturn(false);
        $smartService->run(Argument::any(),Argument::any())->shouldBeCalled()->willReturn($smartResult);
        $this->mockInvalidPost($request, $mvcEvent, $smartResult, $smartService, $params, $action, $postParameters, $viewModelBuilder);
    }

    /**
     * @param $request
     * @param $mvcEvent
     * @param $params
     * @param $smartResult
     * @param $smartService
     * @param $action
     * @param $viewModelBuilder
     */
    private function mockGet($request, $mvcEvent, $params, $smartResult, $smartService, $action, $viewModelBuilder)
    {
        $prophet = new Prophet();

        $routeMatch = $prophet->prophesize('\Zend\Mvc\Router\RouteMatch');
        $routeMatch->getParam('action', Argument::any())->willReturn($action);
        $routeMatch->getParam('id', Argument::any())->willReturn(1);

        $request->isXmlHttpRequest()->willReturn(false);
        $request->isPost()->shouldBeCalled()->willReturn(false);
        $request->getPost()->shouldNotBeCalled();

        $viewModelBuilder->build($request, $smartResult, $action)->shouldBeCalled();

        $mvcEvent->getRouteMatch()->willReturn($routeMatch);
        $mvcEvent->getRequest()->willReturn($request);
        $mvcEvent->setRequest(Argument::any())->willReturn($mvcEvent);
        $mvcEvent->setResponse(Argument::any())->willReturn($mvcEvent);
        $mvcEvent->setTarget(Argument::any())->willReturn($mvcEvent);
        $mvcEvent->setName(Argument::any())->willReturn($mvcEvent);
        $mvcEvent->stopPropagation(Argument::any())->willReturn($mvcEvent);
        $mvcEvent->propagationIsStopped(Argument::any())->willReturn($mvcEvent);
        $mvcEvent->setResult(Argument::any())->willReturn($mvcEvent);

        $this->setViewModelBuilder($viewModelBuilder);
        $this->setSmartService($smartService);
        $this->setEvent($mvcEvent);
        $this->dispatch($request);
    }

    /**
     * @param $request
     * @param $mvcEvent
     * @param $pluginManager
     * @param $params
     * @param $action
     * @param $postParameters
     */
    private function mockValidPost($request, $mvcEvent, $pluginManager, $params, $action, $postParameters)
    {
        $prophet = new Prophet();

        $routeMatch = $prophet->prophesize('\Zend\Mvc\Router\RouteMatch');
        $routeMatch->getParam('action', Argument::any())->willReturn($action);
        $routeMatch->getParam('id', Argument::any())->willReturn(1);

        $pluginManager->setController(Argument::any())->shouldBeCalled();
        $this->setPluginManager($pluginManager);

        $request->isXmlHttpRequest()->willReturn(false);
        $request->isPost()->willReturn(true);
        $request->getPost()->shouldBeCalled();
        $request->getPost()->willReturn($postParameters);

        $mvcEvent->getRouteMatch()->willReturn($routeMatch);
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

    /**
     * @param $request
     * @param $mvcEvent
     * @param $smartService
     * @param $params
     * @param $action
     * @param $postParameters
     * @param $viewModelBuilder
     */
    private function mockInValidPost($request, $mvcEvent, $smartResult, $smartService, $params, $action, $postParameters, $viewModelBuilder)
    {
        $prophet = new Prophet();

        $routeMatch = $prophet->prophesize('\Zend\Mvc\Router\RouteMatch');
        $routeMatch->getParam('action', Argument::any())->willReturn($action);
        $routeMatch->getParam('id', Argument::any())->willReturn(1);
        $entity = new \StdClass();

        $viewModelBuilder->build($request, $smartResult, $action)->shouldBeCalled();
        $this->setViewModelBuilder($viewModelBuilder);
        $this->setSmartService($smartService);

        $request->isXmlHttpRequest()->willReturn(false);
        $request->isPost()->willReturn(true);
        $request->getPost()->shouldBeCalled()->willReturn($postParameters);

        $mvcEvent->getRouteMatch()->willReturn($routeMatch);
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
