<?php

namespace spec\Phpro\SmartCrud\Listener;

use PhpSpec\ObjectBehavior;
use Phpro\SmartCrud\Event\CrudEvent;
use Prophecy\Argument;
use Prophecy\Prophet;

/**
 * Class FlashMessengerSpec
 *
 * @package spec\Phpro\SmartCrud\Listener
 */
class FlashMessengerSpec extends ObjectBehavior
{

    /**
     * Mock the flashmessenger
     *
     * @param $flashMessenger
     */
    protected function mockFlashMessenger($flashMessenger)
    {
        $prophet = new Prophet();
        $serviceManager = $prophet->prophesize('\Zend\ServiceManager\ServiceManager');
        $pluginManager = $prophet->prophesize('\Zend\Mvc\Controller\PluginManager');

        $this->setServiceManager($serviceManager);
        $serviceManager->get('ControllerPluginManager')->willReturn($pluginManager);
        $pluginManager->get('flashmessenger')->willReturn($flashMessenger->getWrappedObject());
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Phpro\SmartCrud\Listener\FlashMessenger');
    }

    public function it_should_extend_zend_listener_aggregate()
    {
        $this->shouldHaveType('Zend\EventManager\AbstractListenerAggregate');
    }

    public function it_should_implement_zend_ServiceManagerAwareInterface()
    {
        $this->shouldImplement('Zend\ServiceManager\ServiceManagerAwareInterface');
    }

    /**
     * @param \Zend\Mvc\Controller\Plugin\FlashMessenger $flashMessenger
     */
    public function it_should_have_a_flashmessenger($flashMessenger)
    {
        $this->mockFlashMessenger($flashMessenger);
        $this->getFlashMessenger()->shouldReturn($flashMessenger);
    }

    /**
     * @param \Zend\EventManager\EventManagerInterface $events
     */
    public function it_should_attach_success_listeners($events)
    {
        $this->attach($events);
        $callback = Argument::type('array');
        $priority = Argument::type('int');
        $events->attach(CrudEvent::AFTER_CREATE, $callback, $priority)->shouldBeCalled();
        $events->attach(CrudEvent::AFTER_UPDATE, $callback, $priority)->shouldBeCalled();
        $events->attach(CrudEvent::AFTER_DELETE, $callback, $priority)->shouldBeCalled();
    }

    /**
     * @param \Zend\EventManager\EventManagerInterface $events
     */
    public function it_should_attach_error_listeners($events)
    {
        $this->attach($events);
        $callback = Argument::type('array');
        $priority = Argument::type('int');
        $events->attach(CrudEvent::INVALID_CREATE, $callback, $priority)->shouldBeCalled();
        $events->attach(CrudEvent::INVALID_UPDATE, $callback, $priority)->shouldBeCalled();
        $events->attach(CrudEvent::INVALID_DELETE, $callback, $priority)->shouldBeCalled();
    }

    /**
     * @param \Zend\Mvc\Controller\Plugin\FlashMessenger $flashMessenger
     * @param \Phpro\SmartCrud\Event\CrudEvent           $event
     */
    public function it_should_add_create_succeeded_message($flashMessenger, $event)
    {
        $this->mockFlashMessenger($flashMessenger);

        $this->createSucceeded($event);
        $flashMessenger->addSuccessMessage(Argument::type('string'))->shouldBeCalled();
    }

    /**
     * @param \Zend\Mvc\Controller\Plugin\FlashMessenger $flashMessenger
     * @param \Phpro\SmartCrud\Event\CrudEvent           $event
     */
    public function it_should_add_update_succeeded_message($flashMessenger, $event)
    {
        $this->mockFlashMessenger($flashMessenger);

        $this->updateSucceeded($event);
        $flashMessenger->addSuccessMessage(Argument::type('string'))->shouldBeCalled();
    }

    /**
     * @param \Zend\Mvc\Controller\Plugin\FlashMessenger $flashMessenger
     * @param \Phpro\SmartCrud\Event\CrudEvent           $event
     */
    public function it_should_add_delete_succeeded_message($flashMessenger, $event)
    {
        $this->mockFlashMessenger($flashMessenger);

        $this->deleteSucceeded($event);
        $flashMessenger->addSuccessMessage(Argument::type('string'))->shouldBeCalled();

    }

    /**
     * @param \Zend\Mvc\Controller\Plugin\FlashMessenger $flashMessenger
     * @param \Phpro\SmartCrud\Event\CrudEvent           $event
     */
    public function it_should_add_create_failed_message($flashMessenger, $event)
    {
        $this->mockFlashMessenger($flashMessenger);

        $this->createFailed($event);
        $flashMessenger->addErrorMessage(Argument::type('string'))->shouldBeCalled();
    }

    /**
     * @param \Zend\Mvc\Controller\Plugin\FlashMessenger $flashMessenger
     * @param \Phpro\SmartCrud\Event\CrudEvent           $event
     */
    public function it_should_add_update_failed_message($flashMessenger, $event)
    {
        $this->mockFlashMessenger($flashMessenger);

        $this->updateFailed($event);
        $flashMessenger->addErrorMessage(Argument::type('string'))->shouldBeCalled();
    }

    /**
     * @param \Zend\Mvc\Controller\Plugin\FlashMessenger $flashMessenger
     * @param \Phpro\SmartCrud\Event\CrudEvent           $event
     */
    public function it_should_add_delete_failed_message($flashMessenger, $event)
    {
        $this->mockFlashMessenger($flashMessenger);

        $this->deleteFailed($event);
        $flashMessenger->addErrorMessage(Argument::type('string'))->shouldBeCalled();
    }

}
