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

    /**
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     * @param \PhproSmartCrud\Service\CrudService $crudService
     * @param \Zend\Form\Form $form
     */
    protected function mockServiceManager($serviceManager, $crudService, $form = null)
    {
        $this->setServiceLocator($serviceManager);
        $serviceManager->get('PhproSmartCrud\Service\CrudServiceFactory')->willReturn($crudService);

        // Default route config
        $prophet = new Prophet();
        $entity = $prophet->prophesize('stdClass');
        $serviceManager->get('stdClass')->willReturn($entity);

        if($form == null) {
            $form = $prophet->prophesize('\Zend\Form\Form');
        }

        // mock methods to prevent errors
        $dummy = Argument::any();
        $crudService->setEntityKey($dummy)->willReturn($crudService);
        $crudService->setFormKey($dummy)->willReturn($crudService);
        $crudService->getForm($dummy)->willReturn($form);
        $crudService->setForm($dummy)->willReturn($crudService);
        $crudService->setEntity($dummy)->willReturn($crudService);
        $crudService->loadEntity(Argument::cetera())->willReturn($entity);
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
            $routeMatch->getParam('index', Argument::any())->willReturn(array(
                                                                             'service' => 'PhproSmartCrud\Service\IndexService' ,
                                                                             'entity'    => null,
                                                                             'form'      => null,
                                                                             'listeners' => array() ,
                                                                        ));
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

        $routeMatch->getParam('listeners', Argument::any())->willReturn(array());
        foreach ($params as $key => $value) {
            $routeMatch->getParam($key, Argument::any())->willReturn($value);
        }
    }

    /**
     * @param $mvcEvent
     */
    protected function mockMvcEvent($mvcEvent)
    {
        $mvcEvent->setRequest(Argument::any())->willReturn($mvcEvent);
        $mvcEvent->setResponse(Argument::any())->willReturn($mvcEvent);
        $mvcEvent->setTarget(Argument::any())->willReturn($mvcEvent);
        $mvcEvent->setName(Argument::any())->willReturn($mvcEvent);
        $mvcEvent->stopPropagation(Argument::any())->willReturn($mvcEvent);
        $mvcEvent->propagationIsStopped(Argument::any())->willReturn($mvcEvent);
        $mvcEvent->setResult(Argument::any())->willReturn($mvcEvent);
        $this->setEvent($mvcEvent);
    }

    /**
     * @param $serviceManager
     */
    protected function mockViewModel($serviceManager)
    {
        $prophet = new Prophet();
        $viewModel = $prophet->prophesize('\Zend\View\Model\ModelInterface');
        $jsonModel = $prophet->prophesize('\PhproSmartCrud\View\Model\JsonModel');

        $serviceManager->get('ViewModelInterface')->willReturn($viewModel);
        $serviceManager->get('PhproSmartCrud\View\Model\JsonModel')->willReturn($jsonModel);
    }

    /**
     * @param array $routeParams
     *
     * @return array
     */
    protected function mergeRouteParams($routeParams)
    {
        $defaults = array(
            'action' => '',
            'entity' => 'stdClass',
            'form' => 'Zend\Form\Form',
            'identifier-name' => 'id',
            'id' => '',
            'create' => array(
                'service'   => 'PhproSmartCrud\Service\CreateServiceFactory' ,
                'entity'    => null,
                'form'      => null,
                'output-model' => '\Zend\View\Model\ModelInterface',
                'listeners' => array() ,
            ),
            'update' => array(
                'service'     => 'PhproSmartCrud\Service\UpdateServiceFactory' ,
                'entity'    => null,
                'form'      => null,
                'output-model' => '\Zend\View\Model\ModelInterface',
                'listeners'   => array() ,
            ),
            'delete' => array(
                'service'   => 'PhproSmartCrud\Service\DeleteServiceFactory' ,
                'entity'    => null,
                'form'      => null,
                'output-model' => '\Zend\View\Model\ModelInterface',
                'listeners' => array() ,
            ),
            'read' => array(
                'service' => 'PhproSmartCrud\Service\ReadServiceFactory' ,
                'entity'    => null,
                'form'      => null,
                'output-model' => '\Zend\View\Model\ModelInterface',
                'listeners' => array() ,
            ),
            'list' => array(
                'service' => 'PhproSmartCrud\Service\ListServiceFactory' ,
                'entity'    => null,
                'form'      => null,
                'output-model' => '\Zend\View\Model\ModelInterface',
                'listeners' => array() ,
            ),
            'listeners' => array(),
        );

        return array_merge($defaults, $routeParams);
    }

    /**
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     * @param array $paramsData
     */
    protected function mockParams($serviceManager, array $paramsData)
    {
        $prophet = new Prophet();
        /** @var \PhproSmartCrud\Service\ParametersService $params  */
        $params = $prophet->prophesize('PhproSmartCrud\Service\ParametersService');
        $params->fromRoute()->willReturn($paramsData);
        $params->fromPost()->willReturn($paramsData);
        $params->fromQuery()->willReturn($paramsData);
        $params->fromRoute('identifier-name', Argument::any())->willReturn('id');
        $params->fromRoute('id', Argument::any())->willReturn(1);

        $serviceManager->get('PhproSmartCrud\Service\ParametersService')->willReturn($params);
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
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     * @param \PhproSmartCrud\Service\AbstractCrudService $crudService
     * @param \stdClass $entity
     */
    public function it_should_configure_entity_key_on_dispatch($mvcEvent, $routeMatch, $serviceManager, $crudService, $entity)
    {
        // Configure routematch
        $this->mockRouteMatch($mvcEvent, $routeMatch, array(
                                                           'entity' => 'stdClass',
                                                           'form' => 'Zend\Form\Form',
                                                      ));
        // Configure service
        $this->mockServiceManager($serviceManager, $crudService);
        // Test
        $this->onDispatch($mvcEvent);
        $routeMatch->getParam('entity', false)->shouldBeCalled();
        $this->getEntityKey()->shouldBe('stdClass');
    }
    /**
     * @param \Zend\Mvc\MvcEvent $mvcEvent
     * @param \Zend\Mvc\Router\Http\RouteMatch $routeMatch
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     * @param \PhproSmartCrud\Service\AbstractCrudService $crudService
     */
    public function it_should_configure_form_key_on_dispatch($mvcEvent, $routeMatch, $serviceManager, $crudService)
    {
        // Configure routematch
        $this->mockRouteMatch($mvcEvent, $routeMatch, array(
                                                           'entity' => 'stdClass',
                                                           'form' => 'Zend\Form\Form',
                                                      ));
        // Configure service
        $this->mockServiceManager($serviceManager, $crudService);
        // Test
        $this->onDispatch($mvcEvent);
        $routeMatch->getParam('form', false)->shouldBeCalled();
        $this->getFormKey()->shouldBe('Zend\Form\Form');
    }

    /**
     * @param \Zend\Mvc\MvcEvent $mvcEvent
     * @param \Zend\Mvc\Router\Http\RouteMatch $routeMatch
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     * @param \PhproSmartCrud\Service\AbstractCrudService $crudService
     */
    public function it_should_configure_action_service_configuration_on_dispatch($mvcEvent, $routeMatch, $serviceManager, $crudService)
    {
        // Configure routematch
        $this->mockRouteMatch($mvcEvent, $routeMatch, array(
                                                           'entity' => 'stdClass',
                                                           'form' => 'Zend\Form\Form',
                                                      ));
        // Configure service
        $this->mockServiceManager($serviceManager, $crudService);
        // Test
        $this->onDispatch($mvcEvent);
        $routeMatch->getParam('index', null)->shouldBeCalled();
        $this->getActionServiceConfiguration()->shouldBeArray();
    }

    /**
     * @param \Zend\Mvc\MvcEvent $mvcEvent
     * @param \Zend\Mvc\Router\Http\RouteMatch $routeMatch
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     * @param \PhproSmartCrud\Service\AbstractCrudService $crudService
     * @param \stdClass $entity
     */
    public function it_should_use_the_identifier_name_to_match_the_entity_id_in_the_route($mvcEvent, $routeMatch, $serviceManager, $crudService, $entity)
    {
        // Configure routematch
        $this->mockRouteMatch($mvcEvent, $routeMatch, array(
                                                           'identifier-name' => 'a-custom-identifier',
                                                           'a-custom-identifier' => '15',
                                                           'entity' => 'stdClass',
                                                           'form' => 'Zend\Form\Form',
                                                      ));

        // Configure service
        $this->mockServiceManager($serviceManager, $crudService);
        $crudService->loadEntity(Argument::cetera())->willReturn($entity);

        // Test
        $this->onDispatch($mvcEvent);
        $routeMatch->getParam('identifier-name', 'id')->shouldBeCalled();
        $routeMatch->getParam('id', null)->shouldNotBeCalled();
        $routeMatch->getParam('a-custom-identifier', null)->shouldBeCalled();
    }

    /**
     * @param \Zend\Mvc\MvcEvent $mvcEvent
     * @param \Zend\Mvc\Router\Http\RouteMatch $routeMatch
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     * @param \PhproSmartCrud\Service\AbstractCrudService $crudService
     * @param \stdClass $entity
     */
    public function it_should_use_id_as_default_identifier_name($mvcEvent, $routeMatch, $serviceManager, $crudService, $entity)
    {
        // Configure routematch
        $this->mockRouteMatch($mvcEvent, $routeMatch, array(
                                                           'id' => '15',
                                                           'entity' => 'stdClass',
                                                           'form' => 'Zend\Form\Form',
                                                      ));

        // Configure service
        $this->mockServiceManager($serviceManager, $crudService);
        $crudService->loadEntity(Argument::cetera())->willReturn($entity);

        // Test
        $this->onDispatch($mvcEvent);
        $routeMatch->getParam('identifier-name', 'id')->shouldBeCalled();
        $routeMatch->getParam('id', null)->shouldBeCalled();
    }

    /**
     * @param \Zend\Mvc\MvcEvent $mvcEvent
     * @param \Zend\Mvc\Router\Http\RouteMatch $routeMatch
     */
    public function it_should_throw_smartCrudException_when_no_entity_is_configured($mvcEvent, $routeMatch)
    {
        $this->mockRouteMatch($mvcEvent, $routeMatch, array(
            'entity' => null,
            'form' => 'ServiceFormKey',
        ));
        $this->shouldThrow('PhproSmartCrud\Exception\SmartCrudException')->duringOnDispatch($mvcEvent);
    }

    /**
     * @param \Zend\Mvc\MvcEvent $mvcEvent
     * @param \Zend\Mvc\Router\Http\RouteMatch $routeMatch
     */
    public function it_should_throw_smartCrudException_when_no_form_is_configured($mvcEvent, $routeMatch)
    {
        $this->mockRouteMatch($mvcEvent, $routeMatch, array(
            'entity' => 'stdClass',
            'form' => null,
        ));
        $this->shouldThrow('PhproSmartCrud\Exception\SmartCrudException')->duringOnDispatch($mvcEvent);
    }

    /**
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     */
    public function it_should_have_fluent_interfaces($serviceManager)
    {
        $dummy = Argument::any();
        $this->setEntityId($dummy)->shouldReturn($this);
        $this->setEntityKey($dummy)->shouldReturn($this);
        $this->setFormKey($dummy)->shouldReturn($this);
        $this->setActionServiceConfiguration($dummy)->shouldReturn($this);
    }

    public function it_should_have_an_entity_key()
    {
        $this->setEntityKey('stdClass');
        $this->getEntityKey()->shouldReturn('stdClass');
    }

    public function it_should_have_a_form_key()
    {
        $this->setFormKey('stdClass');
        $this->getFormKey()->shouldReturn('stdClass');
    }

    public function it_should_have_an_entity_id()
    {
        $this->setEntityId(10);
        $this->getEntityId()->shouldReturn(10);
    }

    public function it_should_have_an_action_service_configuration()
    {
        $config = array('config');
        $this->setActionServiceConfiguration($config);
        $this->getActionServiceConfiguration()->shouldReturn($config);
    }

    /**
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     * @param \PhproSmartCrud\Service\CreateService $service
     * @param \Zend\Http\PhpEnvironment\Request $request
     *
     */
    public function it_should_get_the_correct_action_service($serviceManager, $service, $request)
    {

        $this->setEntityId(1);
        $this->setEntityKey('stdClass');
        $this->setFormKey('formKey');
        $this->setActionServiceConfiguration(array('service' => 'PhproSmartCrud\Service\CreateServiceFactory'));
        $this->setServiceLocator($serviceManager);
        $service->setFormKey(Argument::exact('formKey'))->shouldBeCalled();
        $service->setEntityKey(Argument::exact('stdClass'))->shouldBeCalled();

        $request->isXmlHttpRequest()->willReturn(false);
        $request->isPost()->willReturn(true);

        $serviceManager->get('PhproSmartCrud\Service\CreateServiceFactory')->shouldBeCalled();
        $serviceManager->get('PhproSmartCrud\Service\CreateServiceFactory')->willReturn($service);
        $this->getActionService()->shouldReturn($service);
    }

    /**
     * @param \Zend\Http\PhpEnvironment\Request $request
     * @param \PhproSmartCrud\Service\ListService $actionService
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     */
    public function it_should_handle_a_get_request_to_the_list_action($request, $actionService, $serviceManager)
    {
        $action ='list';

        $request->isXmlHttpRequest()->willReturn(false);
        $request->isPost()->willReturn(false);
        $request->getPost()->shouldNotBeCalled();

        $actionService->getList(Argument::any())->shouldNotBeCalled();
        $actionService->getForm(Argument::any())->shouldBeCalled();
        $actionService->loadEntity(Argument::any())->shouldBeCalled();

        /** @var \Zend\Mvc\Router\Routematch $routeMatch  */
        $routeParams = $this->mergeRouteParams(array('action' => $action));

        $prophet = new Prophet();
        $viewModel = $prophet->prophesize($routeParams[$action]['output-model']);
        $serviceManager->get($routeParams[$action]['output-model'])->shouldBeCalled();
        $serviceManager->get($routeParams[$action]['output-model'])->willReturn($viewModel);

        $this->mockActionDispatch($routeParams, $request, $actionService, $serviceManager);
        $this->listAction()->shouldBeAnInstanceOf('\Zend\View\Model\ModelInterface');

    }

    /**
     * @param \Zend\Http\PhpEnvironment\Request $request
     * @param \PhproSmartCrud\Service\CreateService $actionService
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     * @param \Zend\Mvc\Controller\PluginManager $pluginManager
     * @param \Zend\Mvc\Controller\Plugin\Redirect $redirectPlugin
     */
    public function it_should_handle_a_get_request_to_the_create_action($request, $actionService, $serviceManager, $pluginManager, $redirectPlugin)
    {
        $action ='create';

        $pluginManager->setController(Argument::any())->shouldBeCalled();
        $pluginManager->get(Argument::any())->shouldNotBeCalled();
        $redirectPlugin->toRoute(Argument::any())->shouldNotBeCalled();

        $this->setPluginManager($pluginManager);

        $request->isXmlHttpRequest()->willReturn(false);
        $request->isPost()->willReturn(false);
        $request->getPost()->shouldNotBeCalled();

        $actionService->create(Argument::any())->shouldNotBeCalled();
        $actionService->getForm(Argument::any())->shouldBeCalled();
        $actionService->loadEntity(Argument::any())->shouldBeCalled();

        /** @var \Zend\Mvc\Router\Routematch $routeMatch  */
        $routeParams = $this->mergeRouteParams(array('action' => $action));

        $prophet = new Prophet();
        $viewModel = $prophet->prophesize($routeParams[$action]['output-model']);
        $serviceManager->get($routeParams[$action]['output-model'])->shouldBeCalled();
        $serviceManager->get($routeParams[$action]['output-model'])->willReturn($viewModel);

        $this->mockActionDispatch($routeParams, $request, $actionService, $serviceManager);
        $this->createAction()->shouldBeAnInstanceOf('\Zend\View\Model\ModelInterface');

    }
    /**
     * @param \Zend\Http\PhpEnvironment\Request $request
     * @param \PhproSmartCrud\Service\CreateService $actionService
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     * @param \Zend\Mvc\Controller\PluginManager $pluginManager
     * @param \Zend\Mvc\Controller\Plugin\Redirect $redirectPlugin
     */
    public function it_should_return_the_view_model_after_an_invalid_post_to_the_create_action($request, $actionService, $serviceManager, $pluginManager, $redirectPlugin)
    {
        $action ='create';

        $pluginManager->setController(Argument::any())->shouldBeCalled();
        $pluginManager->get(Argument::exact('redirect'), null)->shouldNotBeCalled();

        $redirectPlugin->toRoute(Argument::exact(null), Argument::exact(array('action' => 'index')))->shouldNotBeCalled();

        $this->setPluginManager($pluginManager);

        $request->isXmlHttpRequest()->willReturn(false);
        $request->isPost()->willReturn(true);
        $request->getPost()->shouldBeCalled();
        $request->getPost()->willReturn(array('property' => 'value'));

        $actionService->create(Argument::exact(array('property' => 'value')))->shouldBeCalled();
        $actionService->create(Argument::exact(array('property' => 'value')))->willReturn(false);
        $actionService->getForm(Argument::any())->shouldBeCalled();
        $actionService->loadEntity(Argument::any())->shouldBeCalled();

        $routeParams = $this->mergeRouteParams(array('action' => $action));

        $prophet = new Prophet();
        $viewModel = $prophet->prophesize($routeParams[$action]['output-model']);
        $serviceManager->get($routeParams[$action]['output-model'])->shouldBeCalled();
        $serviceManager->get($routeParams[$action]['output-model'])->willReturn($viewModel);

        $this->mockActionDispatch($routeParams, $request, $actionService, $serviceManager);
        $this->createAction()->shouldBeAnInstanceOf('\Zend\View\Model\ModelInterface');

    }

    /**
     * @param \Zend\Http\PhpEnvironment\Request $request
     * @param \PhproSmartCrud\Service\CreateService $actionService
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     * @param \Zend\Mvc\Controller\PluginManager $pluginManager
     * @param \Zend\Mvc\Controller\Plugin\Redirect $redirectPlugin
     */
    public function it_should_redirect_to_the_index_after_a_successful_post_to_the_create_action($request, $actionService, $serviceManager, $pluginManager, $redirectPlugin)
    {
        $action ='create';

        $pluginManager->setController(Argument::any())->shouldBeCalled();
        $pluginManager->get(Argument::exact('redirect'), null)->shouldBeCalled();
        $pluginManager->get(Argument::exact('redirect'), null)->willReturn($redirectPlugin);

        $redirectPlugin->toRoute(Argument::exact(null), Argument::exact(array('action' => 'index')))->shouldBeCalled();
        $redirectPlugin->toRoute(Argument::exact(null), array('action' => 'index'))->willReturn('mockRedirect');

        $this->setPluginManager($pluginManager);

        $request->isXmlHttpRequest()->willReturn(false);
        $request->isPost()->willReturn(true);
        $request->getPost()->shouldBeCalled();
        $request->getPost()->willReturn(array('property' => 'value'));

        $actionService->create(Argument::exact(array('property' => 'value')))->shouldBeCalled();
        $actionService->create(Argument::exact(array('property' => 'value')))->willReturn(true);
        $actionService->getForm(Argument::any())->shouldNotBeCalled();
        $actionService->loadEntity(Argument::any())->shouldNotBeCalled();

        $routeParams = $this->mergeRouteParams(array('action' => $action));

        $serviceManager->get($routeParams[$action]['output-model'])->shouldNotBeCalled();

        $this->mockActionDispatch($routeParams, $request, $actionService, $serviceManager);
        $this->createAction()->shouldReturn('mockRedirect');

    }

    /**
     * @param \Zend\Http\PhpEnvironment\Request $request
     * @param \PhproSmartCrud\Service\UpdateService $actionService
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     */
    public function it_should_handle_a_get_request_to_the_update_action($request, $actionService, $serviceManager)
    {
        $action ='update';

        $request->isXmlHttpRequest()->willReturn(false);
        $request->isPost()->willReturn(false);
        $request->getPost()->shouldNotBeCalled();

        $actionService->update(Argument::any())->shouldNotBeCalled();
        $actionService->getForm(Argument::any())->shouldBeCalled();
        $actionService->loadEntity(Argument::any())->shouldBeCalled();


        $routeParams = $this->mergeRouteParams(array('action' => $action, 'id' => '1'));

        $prophet = new Prophet();
        $viewModel = $prophet->prophesize($routeParams[$action]['output-model']);
        $serviceManager->get($routeParams[$action]['output-model'])->shouldBeCalled();
        $serviceManager->get($routeParams[$action]['output-model'])->willReturn($viewModel);

        $this->mockActionDispatch($routeParams, $request, $actionService, $serviceManager);
        $this->updateAction()->shouldBeAnInstanceOf('\Zend\View\Model\ModelInterface');

    }

    /**
     * @param \Zend\Http\PhpEnvironment\Request $request
     * @param \PhproSmartCrud\Service\UpdateService $actionService
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     */
    public function it_should_return_the_view_model_after_an_invalid_post_to_the_update_action($request, $actionService, $serviceManager)
    {
        $action ='update';

        $request->isXmlHttpRequest()->willReturn(false);
        $request->isPost()->willReturn(true);
        $request->getPost()->shouldBeCalled();
        $request->getPost()->willReturn(array('property' => 'value'));

        $actionService->update(Argument::exact('1'),Argument::exact(array('property' => 'value')))->shouldBeCalled();
        $actionService->update(Argument::exact('1'),Argument::exact(array('property' => 'value')))->willReturn(false);
        $actionService->getForm(Argument::any())->shouldBeCalled();
        $actionService->loadEntity(Argument::any())->shouldBeCalled();

        $routeParams = $this->mergeRouteParams(array('action' => $action, 'id' => '1'));

        $prophet = new Prophet();
        $viewModel = $prophet->prophesize($routeParams[$action]['output-model']);
        $serviceManager->get($routeParams[$action]['output-model'])->shouldBeCalled();
        $serviceManager->get($routeParams[$action]['output-model'])->willReturn($viewModel);

        $this->mockActionDispatch($routeParams, $request, $actionService, $serviceManager);
        $this->updateAction()->shouldBeAnInstanceOf('\Zend\View\Model\ModelInterface');

    }

    /**
     * @param \Zend\Http\PhpEnvironment\Request $request
     * @param \PhproSmartCrud\Service\UpdateService $actionService
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     * @param \Zend\Mvc\Controller\PluginManager $pluginManager
     * @param \Zend\Mvc\Controller\Plugin\Redirect $redirectPlugin
     */
    public function it_should_redirect_to_the_view_action_after_a_valid_post_to_the_update_action($request, $actionService, $serviceManager, $pluginManager, $redirectPlugin)
    {
        $action ='update';

        $pluginManager->setController(Argument::any())->shouldBeCalled();
        $pluginManager->get(Argument::exact('redirect'), null)->shouldBeCalled();
        $pluginManager->get(Argument::exact('redirect'), null)->willReturn($redirectPlugin);

        $redirectPlugin->toRoute(Argument::exact(null), Argument::exact(array('action' => 'view')))->shouldBeCalled();
        $redirectPlugin->toRoute(Argument::exact(null), array('action' => 'view'))->willReturn('mockRedirect');

        $this->setPluginManager($pluginManager);

        $request->isXmlHttpRequest()->willReturn(false);
        $request->isPost()->willReturn(true);
        $request->getPost()->shouldBeCalled();
        $request->getPost()->willReturn(array('property' => 'value'));

        $actionService->update(Argument::exact('1'),Argument::exact(array('property' => 'value')))->shouldBeCalled();
        $actionService->update(Argument::exact('1'),Argument::exact(array('property' => 'value')))->willReturn(true);
        $actionService->getForm(Argument::any())->shouldNotBeCalled();
        $actionService->loadEntity(Argument::any())->shouldNotBeCalled();

        $routeParams = $this->mergeRouteParams(array('action' => $action, 'id' => '1'));

        $prophet = new Prophet();
        $viewModel = $prophet->prophesize($routeParams[$action]['output-model']);
        $serviceManager->get($routeParams[$action]['output-model'])->shouldNotBeCalled();

        $this->mockActionDispatch($routeParams, $request, $actionService, $serviceManager);
        $this->updateAction()->shouldReturn('mockRedirect');

    }
    /**
     * @param \Zend\Http\PhpEnvironment\Request $request
     * @param \PhproSmartCrud\Service\DeleteService $actionService
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     */
    public function it_should_return_the_view_model_after_an_invalid_post_to_the_delete_action($request, $actionService, $serviceManager)
    {
        $action ='delete';

        $request->isXmlHttpRequest()->willReturn(false);
        $request->isPost()->willReturn(true);
        $request->getPost()->shouldBeCalled();
        $request->getPost()->willReturn(array('property' => 'value'));

        $actionService->delete(Argument::exact('1'),Argument::exact(array('property' => 'value')))->shouldBeCalled();
        $actionService->delete(Argument::exact('1'),Argument::exact(array('property' => 'value')))->willReturn(false);
        $actionService->getForm(Argument::any())->shouldBeCalled();
        $actionService->loadEntity(Argument::any())->shouldBeCalled();

        $routeParams = $this->mergeRouteParams(array('action' => $action, 'id' => '1'));

        $prophet = new Prophet();
        $viewModel = $prophet->prophesize($routeParams[$action]['output-model']);
        $serviceManager->get($routeParams[$action]['output-model'])->shouldBeCalled();
        $serviceManager->get($routeParams[$action]['output-model'])->willReturn($viewModel);

        $this->mockActionDispatch($routeParams, $request, $actionService, $serviceManager);
        $this->deleteAction()->shouldBeAnInstanceOf('\Zend\View\Model\ModelInterface');

    }
    /**
     * @param \Zend\Http\PhpEnvironment\Request $request
     * @param \PhproSmartCrud\Service\DeleteService $actionService
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     * @param \Zend\Mvc\Controller\PluginManager $pluginManager
     * @param \Zend\Mvc\Controller\Plugin\Redirect $redirectPlugin
     */
    public function it_should_redirect_to_the_index_action_after_a_valid_post_to_the_delete_action($request, $actionService, $serviceManager, $pluginManager, $redirectPlugin)
    {
        $action ='delete';

        $pluginManager->setController(Argument::any())->shouldBeCalled();
        $pluginManager->get(Argument::exact('redirect'), null)->shouldBeCalled();
        $pluginManager->get(Argument::exact('redirect'), null)->willReturn($redirectPlugin);

        $redirectPlugin->toRoute(Argument::exact(null), Argument::exact(array('action' => 'index')))->shouldBeCalled();
        $redirectPlugin->toRoute(Argument::exact(null), array('action' => 'index'))->willReturn('mockRedirect');

        $this->setPluginManager($pluginManager);

        $request->isXmlHttpRequest()->willReturn(false);
        $request->isPost()->willReturn(true);
        $request->getPost()->shouldBeCalled();
        $request->getPost()->willReturn(array('property' => 'value'));

        $actionService->delete(Argument::exact('1'),Argument::exact(array('property' => 'value')))->shouldBeCalled();
        $actionService->delete(Argument::exact('1'),Argument::exact(array('property' => 'value')))->willReturn(true);
        $actionService->getForm(Argument::any())->shouldNotBeCalled();
        $actionService->loadEntity(Argument::any())->shouldNotBeCalled();

        $routeParams = $this->mergeRouteParams(array('action' => $action, 'id' => '1'));

        $prophet = new Prophet();
        $viewModel = $prophet->prophesize($routeParams[$action]['output-model']);
        $serviceManager->get($routeParams[$action]['output-model'])->shouldNotBeCalled();

        $this->mockActionDispatch($routeParams, $request, $actionService, $serviceManager);

        $this->deleteAction()->shouldReturn('mockRedirect');

    }

    /**
     * @param \Zend\Http\PhpEnvironment\Request $request
     * @param \PhproSmartCrud\Service\ReadService $actionService
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     */
    public function it_should_handle_a_get_request_to_the_read_action($request, $actionService, $serviceManager)
    {
        $action ='read';

        $request->isXmlHttpRequest()->willReturn(false);
        $request->isPost()->willReturn(false);
        $request->getPost()->shouldNotBeCalled();

        $actionService->read(Argument::any())->shouldNotBeCalled();
        $actionService->getForm(Argument::any())->shouldBeCalled();
        $actionService->loadEntity(Argument::any())->shouldBeCalled();

        $routeParams = $this->mergeRouteParams(array('action' => $action, 'id' => '1'));

        $prophet = new Prophet();
        $viewModel = $prophet->prophesize($routeParams[$action]['output-model']);
        $serviceManager->get($routeParams[$action]['output-model'])->shouldBeCalled();
        $serviceManager->get($routeParams[$action]['output-model'])->willReturn($viewModel);


        $this->mockActionDispatch($routeParams, $request, $actionService, $serviceManager);
        $this->readAction()->shouldBeAnInstanceOf('\Zend\View\Model\ModelInterface');

    }


    /**
     * @param \Zend\Http\PhpEnvironment\Request $request
     * @param \PhproSmartCrud\Service\ListService $actionService
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     */
    public function it_should_return_json_model_on_ajax($request, $actionService, $serviceManager)
    {
        $action ='list';

        $request->isXmlHttpRequest()->willReturn(true);
        $request->isPost()->willReturn(false);
        $request->getPost()->shouldNotBeCalled();

        $actionService->getList(Argument::any())->shouldNotBeCalled();
        $actionService->getForm(Argument::any())->shouldNotBeCalled();
        $actionService->loadEntity(Argument::any())->shouldNotBeCalled();

        $routeParams = $this->mergeRouteParams(array('action' => $action));

        $prophet = new Prophet();
        $viewModel = $prophet->prophesize('\PhproSmartCrud\View\Model\JsonModel');
        $serviceManager->get('PhproSmartCrud\View\Model\JsonModel')->shouldBeCalled();
        $serviceManager->get('PhproSmartCrud\View\Model\JsonModel')->willReturn($viewModel);

        $this->mockActionDispatch($routeParams, $request, $actionService, $serviceManager);
        $this->listAction()->shouldBeAnInstanceOf('\PhproSmartCrud\View\Model\JsonModel');

    }

    /**
     * @param \PhproSmartCrud\Service\UpdateService $actionService
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     * @param \Zend\EventManager\EventManager $eventManager
     */
    public function it_should_configure_listeners_in_the_action_service($actionService, $serviceManager, $eventManager)
    {
        $this->setEntityId(1);
        $this->setEntityKey('stdClass');
        $this->setFormKey('formKey');
        $this->setActionServiceConfiguration(array(
                                                  'service' => 'PhproSmartCrud\Service\UpdateServiceFactory',
                                                  'listeners' => array('listener1', 'listener2')
                                             ));


        $serviceManager->has('listener1')->shouldBeCalled();
        $serviceManager->has('listener1')->willReturn(true);
        $serviceManager->get('listener1')->shouldBeCalled();
        $serviceManager->get('listener1')->willReturn('listener1');

        $serviceManager->has('listener2')->shouldBeCalled();
        $serviceManager->has('listener2')->willReturn(true);
        $serviceManager->get('listener2')->shouldBeCalled();
        $serviceManager->get('listener2')->willReturn('listener2');

        $this->setServiceLocator($serviceManager);

        $actionService->setFormKey(Argument::exact('formKey'))->shouldBeCalled();
        $actionService->setEntityKey(Argument::exact('stdClass'))->shouldBeCalled();
        $actionService->getEventManager()->shouldBeCalled();
        $actionService->getEventManager()->willReturn($eventManager);

        $eventManager->attach('listener1')->shouldBeCalled();
        $eventManager->attach('listener2')->shouldBeCalled();


        $serviceManager->get('PhproSmartCrud\Service\UpdateServiceFactory')->shouldBeCalled();
        $serviceManager->get('PhproSmartCrud\Service\UpdateServiceFactory')->willReturn($actionService);
        $this->getActionService()->shouldReturn($actionService);
    }
    /**
     * @param \PhproSmartCrud\Service\UpdateService $actionService
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     * @param \Zend\EventManager\EventManager $eventManager
     */
    public function it_should_throw_exception_when_listeners_are_not_configured($actionService, $serviceManager, $eventManager)
    {
        $this->setEntityId(1);
        $this->setEntityKey('stdClass');
        $this->setFormKey('formKey');
        $this->setActionServiceConfiguration(array(
                                                  'service' => 'PhproSmartCrud\Service\UpdateServiceFactory',
                                                  'listeners' => array('unregisteredListener')
                                             ));


        $serviceManager->has('unregisteredListener')->shouldBeCalled();
        $serviceManager->has('unregisteredListener')->willReturn(false);
        $serviceManager->get('unregisteredListener')->shouldNotBeCalled();

        $this->setServiceLocator($serviceManager);

        $actionService->setFormKey(Argument::exact('formKey'))->shouldBeCalled();
        $actionService->setEntityKey(Argument::exact('stdClass'))->shouldBeCalled();
        $actionService->getEventManager()->shouldNotBeCalled();
        $actionService->getEventManager()->willReturn($eventManager);

        $eventManager->attach('unregisteredListener')->shouldNotBeCalled();

        $serviceManager->get('PhproSmartCrud\Service\UpdateServiceFactory')->shouldBeCalled();
        $serviceManager->get('PhproSmartCrud\Service\UpdateServiceFactory')->willReturn($actionService);
        $this->shouldThrow('PhproSmartCrud\Exception\SmartCrudException')->duringGetActionService();
    }


    protected function mockActionDispatch($routeParams, $request, $actionService, $serviceManager)
    {
        $prophet = new Prophet();

        $action = $routeParams['action'];

        $actionService->setFormKey(Argument::exact('Zend\Form\Form'))->shouldBeCalled();
        $actionService->setEntityKey(Argument::exact('stdClass'))->shouldBeCalled();

        $serviceManager->get($routeParams[$action]['service'])->shouldBeCalled();
        $serviceManager->get($routeParams[$action]['service'])->willReturn($actionService);

        $this->setServiceLocator($serviceManager);

        $routeMatch = $prophet->prophesize('\Zend\Mvc\Router\Routematch');
        $routeMatch->setParam(Argument::any(), Argument::any())->willReturn($routeMatch);
        foreach ($routeParams as $key => $value) {
            $routeMatch->getParam($key, Argument::cetera())->willReturn($value);
        }
        $router = new SmartCrudRouter('test');

        /** @var \Zend\Mvc\MvcEvent $mvcEvent  */
        $mvcEvent = $prophet->prophesize('\Zend\Mvc\MvcEvent');
        $mvcEvent->getRouter()->willReturn($router);
        $mvcEvent->getRouteMatch()->willReturn($routeMatch);
        $this->mockMvcEvent($mvcEvent);

        // Configure and dispatch request
        $this->mockParams($serviceManager, array());
        $this->dispatch($request);

    }
}
