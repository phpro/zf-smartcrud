<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/veewee/PhproSmartCrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace spec\PhproSmartCrud\Service;

use PhproSmartCrud\Event\CrudEvent;
use Prophecy\Argument;
use Prophecy\Prophet;

/**
 * Class CrudServiceSpec
 *
 * @package spec\PhproSmartCrud\Service
 */
class CrudServiceSpec extends AbstractCrudServiceSpec
{
    /**
     * @param \PhproSmartCrud\Gateway\AbstractCrudGateway $gateway
     * @param \Zend\EventManager\EventManager $eventManager
     * @param \stdClass $entity
     */
    public function let($gateway, $eventManager, $entity)
    {
        parent::let($gateway, $eventManager, $entity);

        // Load stubs
        $prophet = new Prophet();
        $serviceManager = $prophet->prophesize('\Zend\ServiceManager\ServiceManager');
        $form = $prophet->prophesize('\Zend\Form\Form');

        // Set stubs
        $this->setForm($form);
        $this->setServiceManager($serviceManager);
    }

    /**
     * Make sure that services have fluent interface.
     *
     * @param string $key
     * @param \PhproSmartCrud\Service\AbstractCrudService $service
     *
     * @return $this
     */
    protected function mockActionService($key, $service)
    {
        // Create mocks
        $prophet = new Prophet();
        $serviceManager = $prophet->prophesize('\Zend\ServiceManager\ServiceManager');

        // Configure ServiceManager
        $this->setServiceManager($serviceManager);
        $serviceManager->has($key)->willReturn(true);
        $serviceManager->get($key)->willReturn($service->getWrappedObject());

        // Make service a fluent interface
        $dummy = Argument::any();
        $service->setEntity($dummy)->willReturn($service);
        $service->setEventManager($dummy)->willReturn($service);
        $service->setParameters($dummy)->willReturn($service);
        $service->setGateway($dummy)->willReturn($service);

        // Add dummy getters
        $service->getEntity()->willReturn($prophet->prophesize('\stdClass')->reveal());
        $service->getEventManager()->willReturn($prophet->prophesize('\Zend\EventManager\EventManager')->reveal());
        $service->getParameters()->willReturn(array());
        $service->getGateway()->willReturn($prophet->prophesize('\PhproSmartCrud\Gateway\AbstractCrudGateway')->reveal());

        return $this;
    }

    /**
     * This mock will make the isValid() method return the wanted value
     *
     * @param bool $returnValue
     *
     * @return $this
     */
    protected function mockValidation($returnValue)
    {
        // Create mocks:
        $prophet = new Prophet();
        $eventManager = $prophet->prophesize('\Zend\EventManager\EventManager');
        $eventResponseCollection = $prophet->prophesize('\Zend\EventManager\ResponseCollection');

        // Event validation:
        $this->setEventManager($eventManager);
        $eventManager->trigger(Argument::cetera())->willReturn($eventResponseCollection);
        $eventResponseCollection->stopped()->willReturn(false);

        // Mock form validation
        $this->mockFormValidation($returnValue);

        return $this;
    }

    /**
     * This mock will make the isValid() method on zend form return the wanted value
     *
     * @param bool $returnValue
     *
     * @return $this
     */
    protected function mockFormValidation($returnValue)
    {
        $prophet = new Prophet();
        $form = $prophet->prophesize('\Zend\Form\Form');

        // Form validation:
        $this->setForm($form);
        $form->setData(Argument::any())->willReturn($form);
        $form->isValid()->willReturn($returnValue);

        return $this;
    }

    /**
     * Mock the event manager so that it passes all requests
     *
     * @param $eventManager
     *
     * @return $this
     */
    protected function mockEventManager($eventManager)
    {
        // Create mocks:
        $prophet = new Prophet();
        $eventResponseCollection = $prophet->prophesize('\Zend\EventManager\ResponseCollection');

        // Event validation:
        $this->setEventManager($eventManager);
        $eventManager->trigger(Argument::any())->willReturn(true);
        $eventManager->trigger(Argument::cetera())->willReturn($eventResponseCollection);
        $eventResponseCollection->stopped()->willReturn(false);

        return $this;
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('PhproSmartCrud\Service\CrudService');
    }

    public function it_should_extend_PhproSmartCrud_AbstractCrudService()
    {
        $this->shouldBeAnInstanceOf('PhproSmartCrud\Service\AbstractCrudService');
    }

    public function it_should_implement_Zend_ServiceManagerAwareInterface()
    {
        $this->shouldImplement('Zend\ServiceManager\ServiceManagerAwareInterface');
    }

    /**
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     */
    public function it_should_have_fluent_interfaces($serviceManager)
    {
        parent::it_should_have_fluent_interfaces();

        $dummy = Argument::any();
        $this->setServiceManager($serviceManager)->shouldReturn($this);
        $this->setForm($dummy)->shouldReturn($this);
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
     * @param \Zend\EventManager\EventManager $eventManager
     * @param \Zend\EventManager\ResponseCollection $eventResponseCollection
     */
    public function it_should_trigger_before_validate_event($eventManager, $eventResponseCollection)
    {
        // Event validation:
        $this->setEventManager($eventManager);
        $eventManager->trigger(Argument::cetera())->willReturn($eventResponseCollection);
        $eventResponseCollection->stopped()->willReturn(false);

        // Test event
        $this->isValid();
        $eventManager->trigger(
            Argument::which('getName', CrudEvent::BEFORE_VALIDATE),
            Argument::type('null'),
            Argument::type('array'),
            Argument::type('callable')
        )->shouldBeCalled();

    }

    /**
     * @param \Zend\EventManager\EventManager $eventManager
     * @param \Zend\EventManager\ResponseCollection $eventResponseCollection
     */
    public function it_should_trigger_after_validate_event($eventManager, $eventResponseCollection)
    {
        // Mock Event validation:
        $this->setEventManager($eventManager);
        $eventManager->trigger(Argument::cetera())->willReturn($eventResponseCollection);
        $eventResponseCollection->stopped()->willReturn(false);

        // Mock Form validation
        $this->mockFormValidation(true);

        // Test event
        $this->isValid();
        $eventManager->trigger(
            Argument::which('getName', CrudEvent::AFTER_VALIDATE),
            Argument::type('null'),
            Argument::type('array'),
            Argument::type('callable')
        )->shouldBeCalled();
    }

    /**
     * @param \Zend\EventManager\EventManager $eventManager
     * @param \Zend\EventManager\ResponseCollection $beforeResponse
     * @param \Zend\EventManager\ResponseCollection $afterResponse
     */
    public function it_should_validate_triggered_validation_events($eventManager, $beforeResponse, $afterResponse)
    {
        // Mock Form validation
        $this->mockFormValidation(true);

        // Event validation:
        $this->setEventManager($eventManager);
        $eventManager->trigger(Argument::which('getName', CrudEvent::BEFORE_VALIDATE), Argument::cetera())->willReturn($beforeResponse);
        $eventManager->trigger(Argument::which('getName', CrudEvent::AFTER_VALIDATE), Argument::cetera())->willReturn($afterResponse);

        // Valid before & after event response:
        $beforeResponse->stopped()->willReturn(false);
        $afterResponse->stopped()->willReturn(false);
        $this->isValid()->shouldReturn(true);

        // Invalid before event response:
        $beforeResponse->stopped()->willReturn(true);
        $afterResponse->stopped()->willReturn(false);
        $this->isValid()->shouldReturn(false);

        // Invalid after event response:
        $beforeResponse->stopped()->willReturn(false);
        $afterResponse->stopped()->willReturn(true);
        $this->isValid()->shouldReturn(false);

        // Invalid before and after event response:
        $beforeResponse->stopped()->willReturn(true);
        $afterResponse->stopped()->willReturn(true);
        $this->isValid()->shouldReturn(false);

    }

    public function it_should_validate_form_data()
    {
        $this->mockValidation(true);
        $this->isValid()->shouldReturn(true);

        $this->mockValidation(false);
        $this->isValid()->shouldReturn(false);
    }

    /**
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     * @param \PhproSmartCrud\Service\AbstractCrudService $crudService
     */
    public function it_should_provide_valid_action_services($serviceManager, $crudService)
    {
        // Validate if the service exists in the service manager
        $serviceManager->has(Argument::any())->willReturn(false);
        $this->shouldThrow('\PhproSmartCrud\Exception\SmartCrudException')->duringGetActionService('invalid-action-key');

        // Validate if the returned service is an abstractCrudService
        $serviceManager->has(Argument::any())->willReturn(true);
        $serviceManager->get(Argument::any())->willReturn(null);
        $this->shouldThrow('\PhproSmartCrud\Exception\SmartCrudException')->duringGetActionService('invalid-service-type');

        // Mock actionservice
        $this->mockActionService('valid-action-service', $crudService);

        // Validate service
        $service = $this->getActionService('valid-action-service');
        $service->shouldReturn($crudService);
        $service->getEntity()->shouldReturnAnInstanceOf('\stdClass');
        $service->getEventManager()->shouldReturnAnInstanceOf('\Zend\EventManager\EventManager');
        $service->getParameters()->shouldReturn(array());
        $service->getGateway()->shouldReturnAnInstanceOf('\PhproSmartCrud\Gateway\AbstractCrudGateway');
    }

    /**
     * @param \PhproSmartCrud\Service\ListService $listService
     */
    public function it_should_fetch_a_list($listService)
    {
        $this->mockActionService('phpro.smartcrud.list', $listService);

        $data = array('param1' => 'value1', 'param2' => 'value2');
        $listService->getList()->willReturn($data);
        $this->getList()->shouldReturn($data);
    }

    /**
     * @param \PhproSmartCrud\Service\CreateService $createService
     */
    public function it_should_create_a_valid_entity($createService)
    {
        $this->mockActionService('phpro.smartcrud.create', $createService);
        $this->mockValidation(true);

        // Test the create
        $createService->create()->willReturn(true);
        $this->create()->shouldReturn(true);
    }

    /**
     * @param \PhproSmartCrud\Service\CreateService $createService
     */
    public function it_should_not_create_an_invalid_entity($createService)
    {
        $this->mockActionService('phpro.smartcrud.create', $createService);
        $this->mockValidation(false);

        // Test the create
        $this->create()->shouldReturn(false);
    }

    /**
     * @param \Zend\EventManager\EventManager $eventManager
     * @param \PhproSmartCrud\Service\CreateService $createService
     */
    public function it_should_trigger_invalid_create_event($eventManager, $createService)
    {
        $this->mockActionService('phpro.smartcrud.create', $createService);
        $this->mockValidation(true);
        $this->mockEventManager($eventManager);
        $createService->create()->willReturn(false);

        $this->create()->shouldReturn(false);
        $eventManager->trigger(Argument::which('getName', CrudEvent::INVALID_CREATE))->shouldBeCalled();
    }

    /**
     * @param \PhproSmartCrud\Service\ReadService $readService
     */
    public function it_should_read_an_entity($readService)
    {
        $this->mockActionService('phpro.smartcrud.read', $readService);

        // Test the read
        $entity = new \stdClass();
        $readService->read()->willReturn($entity);
        $this->read()->shouldReturn($entity);
    }

    /**
     * @param \PhproSmartCrud\Service\UpdateService $updateService
     */
    public function it_should_update_a_valid_entity($updateService)
    {
        $this->mockActionService('phpro.smartcrud.update', $updateService);
        $this->mockValidation(true);

        // Test the update
        $updateService->update()->willReturn(true);
        $this->update()->shouldReturn(true);
    }

    /**
     * @param \PhproSmartCrud\Service\UpdateService $updateService
     */
    public function it_should_not_update_an_invalid_entity($updateService)
    {
        $this->mockActionService('phpro.smartcrud.update', $updateService);
        $this->mockValidation(false);

        // Test the update
        $this->update()->shouldReturn(false);
    }

    /**
     * @param \Zend\EventManager\EventManager $eventManager
     * @param \PhproSmartCrud\Service\UpdateService $updateService
     */
    public function it_should_trigger_invalid_update_event($eventManager, $updateService)
    {
        $this->mockActionService('phpro.smartcrud.update', $updateService);
        $this->mockValidation(true);
        $this->mockEventManager($eventManager);
        $updateService->update()->willReturn(false);

        $this->update()->shouldReturn(false);
        $eventManager->trigger(Argument::which('getName', CrudEvent::INVALID_UPDATE))->shouldBeCalled();
    }

    /**
     * @param \PhproSmartCrud\Service\DeleteService $deleteService
     */
    public function it_should_delete_an_entity($deleteService)
    {
        $this->mockActionService('phpro.smartcrud.delete', $deleteService);
        $this->mockValidation(true);

        // Test the update
        $deleteService->delete()->willReturn(true);
        $this->delete()->shouldReturn(true);
    }

    /**
     * @param \Zend\EventManager\EventManager $eventManager
     * @param \PhproSmartCrud\Service\DeleteService $deleteService
     */
    public function it_should_trigger_invalid_delete_event($eventManager, $deleteService)
    {
        $this->mockActionService('phpro.smartcrud.delete', $deleteService);
        $this->mockEventManager($eventManager);
        $deleteService->delete()->willReturn(false);

        $this->delete()->shouldReturn(false);
        $eventManager->trigger(Argument::which('getName', CrudEvent::INVALID_DELETE))->shouldBeCalled();

    }

}
