<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/veewee/PhproSmartCrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace spec\PhproSmartCrud\Controller;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Prophecy\Prophet;

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
     */
    protected function mockServiceManager($serviceManager, $crudService)
    {
        $this->setServiceLocator($serviceManager);
        $serviceManager->get('PhproSmartCrud\Service\CrudServiceFactory')->willReturn($crudService);

        // Default route config
        $prophet = new Prophet();
        $entity = $prophet->prophesize('stdClass');
        $serviceManager->get('stdClass')->willReturn($entity);
        $serviceManager->get('Zend\Form\Form')->willReturn($prophet->prophesize('Zend\Form\Form'));

        // mock methods to prevent errors
        $dummy = Argument::any();
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
        $routeMatch->getParam('action', Argument::any())->willReturn('index');
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
            'listeners' => array(),
            'output' => array(
                'list' => 'ViewModelInterface',
                'create' => 'ViewModelInterface',
                'post-create' => 'ViewModelInterface',
                'read' => 'ViewModelInterface',
                'update' => 'ViewModelInterface',
                'post-update' => 'ViewModelInterface',
                'delete' => 'ViewModelInterface',
            )
        );

        return array_merge($defaults, $routeParams);
    }

    /**
     * @param array $routeParams
     * @param \Zend\Http\PhpEnvironment\Request $request
     * @param \PhproSmartCrud\Service\CrudService $crudService
     */
    protected function mockControllerAction($routeParams, $request, $crudService)
    {
        // Mock services:
        $prophet = new Prophet();
        $serviceManager = $prophet->prophesize('\Zend\ServiceManager\ServiceManager');
        $this->mockServiceManager($serviceManager, $crudService);
        $this->mockViewModel($serviceManager);
        $this->setCrudService($crudService);

        // create routematch
        /** @var \Zend\Mvc\Router\Routematch $routeMatch  */
        $routeMatch = $prophet->prophesize('\Zend\Mvc\Router\Routematch');
        $routeMatch->setParam(Argument::any(), Argument::any())->willReturn($routeMatch);
        foreach ($routeParams as $key => $value) {
            $routeMatch->getParam($key, Argument::cetera())->willReturn($value);
        }

        // Configure mvc Event
        /** @var \Zend\Mvc\MvcEvent $mvcEvent  */
        $mvcEvent = $prophet->prophesize('\Zend\Mvc\MvcEvent');
        $mvcEvent->getRouteMatch()->willReturn($routeMatch);
        $this->mockMvcEvent($mvcEvent);

        // Configure and dispatch request
        $this->mockParams($serviceManager, array());
        $this->dispatch($request);
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
     * @param \PhproSmartCrud\Service\CrudService $crudService
     * @param \stdClass $entity
     */
    public function it_should_configure_entity_on_dispatch($mvcEvent, $routeMatch, $serviceManager, $crudService, $entity)
    {
        // Configure routematch
        $this->mockRouteMatch($mvcEvent, $routeMatch, array(
            'entity' => 'stdClass',
            'form' => 'Zend\Form\Form',
        ));

        // Configure service
        $this->mockServiceManager($serviceManager, $crudService);
        $crudService->loadEntity(Argument::cetera())->willReturn($entity);

        // Test
        $this->onDispatch($mvcEvent);
        $crudService->loadEntity('stdClass', null)->shouldBeCalled();
        $this->getEntity()->shouldBe($entity);
        $crudService->setEntity($entity)->shouldBeCalled();
    }

    /**
     * @param \Zend\Mvc\MvcEvent $mvcEvent
     * @param \Zend\Mvc\Router\Http\RouteMatch $routeMatch
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     * @param \PhproSmartCrud\Service\CrudService $crudService
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
        $crudService->loadEntity('stdClass', 15)->shouldBeCalled();
    }

    /**
     * @param \Zend\Mvc\MvcEvent $mvcEvent
     * @param \Zend\Mvc\Router\Http\RouteMatch $routeMatch
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     * @param \PhproSmartCrud\Service\CrudService $crudService
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
        $crudService->loadEntity('stdClass', 15)->shouldBeCalled();
    }

    /**
     * @param \Zend\Mvc\MvcEvent $mvcEvent
     * @param \Zend\Mvc\Router\Http\RouteMatch $routeMatch
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     * @param \PhproSmartCrud\Service\CrudService $crudService
     * @param \Zend\Form\Form $form
     */
    public function it_should_configure_form_on_dispatch($mvcEvent, $routeMatch, $serviceManager, $crudService, $form)
    {
        // Configure routematch
        $this->mockRouteMatch($mvcEvent, $routeMatch, array(
            'entity' => 'stdClass',
            'form' => 'ServiceFormKey'
        ));

        // Configure service
        $serviceManager->get('ServiceFormKey')->willReturn($form);
        $this->mockServiceManager($serviceManager, $crudService);

        // Test
        $this->onDispatch($mvcEvent);
        $serviceManager->get('ServiceFormKey')->shouldBeCalled();
        $this->getForm()->shouldBe($form);

        // Service config
        $crudService->setForm($form)->shouldBeCalled();

        // Form config:
        $form->bind(Argument::type('stdClass'))->shouldBeCalled();
        $form->setBindOnValidate(true)->shouldBeCalled();
    }

    /**
     * @param \Zend\Mvc\MvcEvent $mvcEvent
     * @param \Zend\Mvc\Router\Http\RouteMatch $routeMatch
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     * @param \PhproSmartCrud\Service\CrudService $crudService
     * @param \stdClass $entity
     * @param \Zend\EventManager\EventManager $eventManager
     */
    public function it_should_configure_listeners_in_the_crud_service_on_dispatch($mvcEvent, $routeMatch,
        $serviceManager, $crudService, $entity, $eventManager)
    {
        // Configure routematch
        $this->mockRouteMatch($mvcEvent, $routeMatch, array(
                                                           'entity' => 'stdClass',
                                                           'form' => 'Zend\Form\Form',
                                                           'listeners' => array('listener1')
                                                      ));
        $dummy = Argument::cetera();

        $this->mockServiceManager($serviceManager, $crudService);
        $serviceManager->has('listener1')->willReturn(true);
        $serviceManager->get('listener1')->willReturn('listener1');

        $crudService->getEventManager()->willReturn($eventManager);

        $serviceManager->has('listener1')->shouldBeCalled();
        $serviceManager->get('listener1')->shouldBeCalled();
        $crudService->getEventManager()->shouldBeCalled();
        $eventManager->attach('listener1')->shouldBeCalled();
        $this->onDispatch($mvcEvent);
    }

    /**
     * @param \Zend\Mvc\MvcEvent $mvcEvent
     * @param \Zend\Mvc\Router\Http\RouteMatch $routeMatch
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     * @param \PhproSmartCrud\Service\CrudService $crudService
     * @param \stdClass $entity
     */
    public function it_should_throw_exception_when_listeners_are_not_configured($mvcEvent, $routeMatch, $serviceManager, $crudService, $entity)
    {
        // Configure routematch
        $this->mockRouteMatch($mvcEvent, $routeMatch, array(
           'entity' => 'stdClass',
           'form' => 'Zend\Form\Form',
           'listeners' => array('listener1')
        ));
        $this->mockServiceManager($serviceManager, $crudService);
        $serviceManager->has('listener1')->willReturn(false);
        $this->shouldThrow('PhproSmartCrud\Exception\SmartCrudException')->duringOnDispatch($mvcEvent);
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
        $this->setForm($dummy)->shouldReturn($this);
        $this->setEntity($dummy)->shouldReturn($this);
    }

    /**
     * @param \Zend\Form\Form $form
     */
    public function it_should_have_a_form($form)
    {
        $this->setForm($form);
        $this->getForm()->shouldReturn($form);
    }

    /**
     * @param \stdClass $entity
     */
    public function it_should_have_an_entity($entity)
    {
        $this->setEntity($entity);
        $this->getEntity()->shouldReturn($entity);
    }

    /**
     * @param \PhproSmartCrud\Service\CrudService $crudService
     */
    public function it_should_have_a_crud_service($crudService)
    {
        $this->setCrudService($crudService);
        $this->getCrudService()->shouldReturn($crudService);
    }

    /**
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     * @param \PhproSmartCrud\Service\CrudService $crudService
     */
    public function it_should_have_a_default_crud_service($serviceManager, $crudService)
    {
        $dummy = Argument::any();
        $this->mockServiceManager($serviceManager, $crudService);

        // validate:
        $this->getCrudService()->shouldReturn($crudService);
        $crudService->setForm($dummy)->shouldBeCalled();
        $crudService->setEntity($dummy)->shouldBeCalled();
    }

    /**
     * @param \Zend\Http\PhpEnvironment\Request $request
     * @param \PhproSmartCrud\Service\CrudService $crudService
     */
    public function it_should_handle_list_action($request, $crudService)
    {
        $routeParams = $this->mergeRouteParams(array('action' => 'list'));
        $crudService->getList()->willReturn(array());
        $request->isXmlHttpRequest()->willReturn(false);
        $this->mockControllerAction($routeParams, $request, $crudService);

        $this->listAction()->shouldBeAnInstanceOf('\Zend\View\Model\ModelInterface');
    }

    /**
     * @param \Zend\Http\PhpEnvironment\Request $request
     * @param \PhproSmartCrud\Service\CrudService $crudService
     */
    public function it_should_handle_create_action($request, $crudService)
    {
        $routeParams = $this->mergeRouteParams(array('action' => 'create'));
        $crudService->create()->willReturn(true);
        $request->isXmlHttpRequest()->willReturn(false);
        $request->isPost()->willReturn(true);
        $this->mockControllerAction($routeParams, $request, $crudService);

        $this->createAction()->shouldBeAnInstanceOf('\Zend\View\Model\ModelInterface');
    }

    /**
     * @param \Zend\Http\PhpEnvironment\Request $request
     * @param \PhproSmartCrud\Service\CrudService $crudService
     */
    public function it_should_handle_read_action($request, $crudService)
    {
        $routeParams = $this->mergeRouteParams(array('action' => 'read'));
        $crudService->read()->willReturn(true);
        $request->isXmlHttpRequest()->willReturn(false);
        $this->mockControllerAction($routeParams, $request, $crudService);

        $this->readAction()->shouldBeAnInstanceOf('\Zend\View\Model\ModelInterface');
    }

    /**
     * @param \Zend\Http\PhpEnvironment\Request $request
     * @param \PhproSmartCrud\Service\CrudService $crudService
     */
    public function it_should_handle_update_action($request, $crudService)
    {
        $routeParams = $this->mergeRouteParams(array('action' => 'update'));
        $crudService->update()->willReturn(true);
        $request->isXmlHttpRequest()->willReturn(false);
        $request->isPost()->willReturn(true);
        $this->mockControllerAction($routeParams, $request, $crudService);

        $this->updateAction()->shouldBeAnInstanceOf('\Zend\View\Model\ModelInterface');
    }

    /**
     * @param \Zend\Http\PhpEnvironment\Request $request
     * @param \PhproSmartCrud\Service\CrudService $crudService
     */
    public function it_should_handle_delete_action($request, $crudService)
    {
        $routeParams = $this->mergeRouteParams(array('action' => 'delete'));
        $crudService->delete()->willReturn(true);
        $request->isXmlHttpRequest()->willReturn(true);
        $this->mockControllerAction($routeParams, $request, $crudService);

        $this->deleteAction()->shouldBeAnInstanceOf('\PhproSmartCrud\View\Model\JsonModel');
    }

    /**
     * @param \Zend\Http\PhpEnvironment\Request $request
     * @param \PhproSmartCrud\Service\CrudService $crudService
     */
    public function it_should_return_json_model_on_ajax($request, $crudService)
    {
        $routeParams = $this->mergeRouteParams(array('action' => 'list'));
        $crudService->getList()->willReturn(array());
        $request->isXmlHttpRequest()->willReturn(true);
        $this->mockControllerAction($routeParams, $request, $crudService);

        $this->listAction()->shouldBeAnInstanceOf('\PhproSmartCrud\View\Model\JsonModel');
    }

    /**
     * @param \Zend\Http\PhpEnvironment\Request $request
     * @param \PhproSmartCrud\Service\CrudService $crudService
     */
    public function it_should_throw_exception_when_output_model_is_not_configured($request, $crudService)
    {
        $routeParams = $this->mergeRouteParams(array('action' => 'does-not-exist-in-output-array'));
        $crudService->getList()->willReturn(array());
        $request->isXmlHttpRequest()->willReturn(false);
        $this->mockControllerAction($routeParams, $request, $crudService);

        $this->shouldThrow('PhproSmartCrud\Exception\SmartCrudException')->duringListAction();
    }

}
