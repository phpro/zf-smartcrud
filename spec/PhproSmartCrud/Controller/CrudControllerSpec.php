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

        // mock methods to prevent errors
        $dummy = Argument::any();
        $crudService->setParameters($dummy)->willReturn($crudService);
        $crudService->setForm($dummy)->willReturn($crudService);
        $crudService->setEntity($dummy)->willReturn($crudService);
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
        $this->mockRouteMatch($mvcEvent, $routeMatch, array('entity' => 'stdClass', 'form' => false));

        // Configure service
        $crudService->loadEntity(Argument::cetera())->willReturn($entity);
        $this->mockServiceManager($serviceManager, $crudService);

        // Test
        $this->onDispatch($mvcEvent);
        $crudService->loadEntity('stdClass', null)->shouldBeCalled();
        $this->getEntity()->shouldBe($entity);
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
        $this->mockRouteMatch($mvcEvent, $routeMatch, array('entity' => false, 'form' => 'ServiceFormKey'));

        // Configure service
        $serviceManager->get('ServiceFormKey')->willReturn($form);
        $this->mockServiceManager($serviceManager, $crudService);

        // Test
        $this->onDispatch($mvcEvent);
        $serviceManager->get('ServiceFormKey')->shouldBeCalled();
        $this->getForm()->shouldBe($form);
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

}
