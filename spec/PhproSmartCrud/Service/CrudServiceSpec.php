<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/veewee/PhproSmartCrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace spec\PhproSmartCrud\Service;

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
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     * @param \Zend\Form\Form $form
     */
    public function let($gateway, $eventManager, $entity, $serviceManager, $form)
    {
        parent::let($gateway, $eventManager, $entity);

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
        $form = $prophet->prophesize('\Zend\Form\Form');

        // Event validation:
        $this->setEventManager($eventManager);
        $eventManager->trigger(Argument::cetera())->willReturn($eventResponseCollection);
        $eventResponseCollection->stopped()->willReturn(false);

        // Form validation:
        $this->setForm($form);
        $form->setData(Argument::any())->willReturn($form);
        $form->isValid()->willReturn($returnValue);

        return $this;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('PhproSmartCrud\Service\CrudService');
    }

    public function it_should_extend_PhproSmartCrud_AbstractCrudService()
    {
        $this->shouldBeAnInstanceOf('PhproSmartCrud\Service\AbstractCrudService');
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

    public function it_should_trigger_validation_events()
    {
        // TODO
    }

    public function it_should_pre_validate_data()
    {
        // TODO
    }

    public function it_should_validate_data()
    {
        // TODO
    }

    public function it_should_post_validate_data()
    {
        // TODO
    }

    public function it_should_provide_valid_action_services()
    {
        // TODO
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

        // Test the create function
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

        // Test the create function
        $this->create()->shouldReturn(false);
    }

    /**
     * @param \PhproSmartCrud\Service\ReadService $readService
     */
    public function it_should_read_an_entity($readService)
    {
        $this->mockActionService('phpro.smartcrud.read', $readService);

        // Test the read function
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

        // Test the create function
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

        // Test the create function
        $this->update()->shouldReturn(false);
    }

    /**
     * @param \PhproSmartCrud\Service\DeleteService $deleteService
     */
    public function it_should_delete_an_entity($deleteService)
    {
        $this->mockActionService('phpro.smartcrud.delete', $deleteService);
        $this->mockValidation(true);

        // Test the create function
        $deleteService->delete()->willReturn(true);
        $this->delete()->shouldReturn(true);
    }

}
