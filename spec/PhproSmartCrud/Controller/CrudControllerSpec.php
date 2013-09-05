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

    public function it_should_implement_Zend_ServiceManagerInterface()
    {
        $this->shouldBeAnInstanceOf('Zend\ServiceManager\ServiceManagerAwareInterface');
    }

    /**
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     * @param \PhproSmartCrud\Service\CrudService $crudService
     */
    protected function mockServiceManager($serviceManager, $crudService)
    {
        $this->setServiceManager($serviceManager);
        $serviceManager->get('phpro.smartcrud')->willReturn($crudService);

        // Default route config
        $prophet = new Prophet();
        $entity = $prophet->prophesize('stdClass');
        $serviceManager->get('stdClass')->willReturn($entity);
        $serviceManager->get('Zend\Form\Form')->willReturn($prophet->prophesize('Zend\Form\Form'));

        // mock methods to prevent errors
        $dummy = Argument::any();
        $crudService->setParameters($dummy)->willReturn($crudService);
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
        $routeMatch->getParam('id', Argument::any())->willReturn(null);

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
     * @param \Zend\Http\PhpEnvironment\Request $request
     * @param array $paramsValues
     */
    protected function mockRequest($request, $paramsValues)
    {
        $prophet = new Prophet();
        $params = $prophet->prophesize('Zend\Stdlib\Parameters');
        $params->toArray()->willReturn($paramsValues);

        $request->getQuery(Argument::cetera())->willReturn($params);
        $request->getPost(Argument::cetera())->willReturn($params);
        $this->dispatch($request);
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
        $serviceManager->get('phpro.smartcrud.view.model.json')->willReturn($jsonModel);
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
            'id' => null,
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

        // Configure request
        $this->mockRequest($request, array());
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
            'form' => 'Zend\Form\Form'
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
        $this->setServiceManager($serviceManager)->shouldReturn($this);
        $this->setForm($dummy)->shouldReturn($this);
        $this->setEntity($dummy)->shouldReturn($this);
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
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     */
    public function it_should_load_service_manager_from_controller_manager($serviceManager)
    {
        $this->setServiceLocator($serviceManager);
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
        $crudService->setParameters($dummy)->shouldBeCalled();
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
