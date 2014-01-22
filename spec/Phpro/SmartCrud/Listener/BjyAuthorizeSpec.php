<?php

namespace spec\Phpro\SmartCrud\Listener;

use PhpSpec\ObjectBehavior;
use Phpro\SmartCrud\Event\CrudEvent;
use Phpro\SmartCrud\Listener\BjyAuthorize;
use Prophecy\Argument;
use Prophecy\Prophet;

class BjyAuthorizeSpec extends ObjectBehavior
{

    /**
     * @param \BjyAuthorize\Service\Authorize $authorizeService
     */
    protected function mockListenerFactory($authorizeService)
    {
        $prophet = new Prophet();
        $serviceManager = $prophet->prophesize('\Zend\ServiceManager\ServiceManager');

        $serviceManager->has('BjyAuthorize\Service\Authorize')->willReturn(true);
        $serviceManager->get('BjyAuthorize\Service\Authorize')->willReturn($authorizeService);

        $this->createService($serviceManager);
    }

    /**
     * Mock the authorization service
     *
     * @param bool $isAllowed
     */
    protected function mockAuthorizeService($isAllowed = true)
    {
        $prophet = new Prophet();
        $authorizeService = $prophet->prophesize('\BjyAuthorize\Service\Authorize');
        $authorizeService->isAllowed(Argument::cetera())->willReturn($isAllowed);

        $this->mockListenerFactory($authorizeService->reveal());
    }

    /**
     * @param \Phpro\SmartCrud\Event\CrudEvent                 $event
     * @param \BjyAuthorize\Service\Authorize                  $authorizeService
     * @param \Zend\Permissions\Acl\Resource\ResourceInterface $resource
     */
    protected function mockEvent($authorizeService, $event, $resource)
    {
        $authorizeService->isAllowed(Argument::cetera())->willReturn(true);
        $this->mockListenerFactory($authorizeService->getWrappedObject());
        $event->getEntity()->willReturn($resource->getWrappedObject());
    }

    public function let()
    {
        $this->mockAuthorizeService(true);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Phpro\SmartCrud\Listener\BjyAuthorize');
    }

    public function it_should_extend_zend_listener_aggregate()
    {
        $this->shouldHaveType('Zend\EventManager\AbstractListenerAggregate');
    }

    public function it_should_implement_zend_FactoryInterface()
    {
        $this->shouldImplement('Zend\ServiceManager\FactoryInterface');
    }

    /**
     * @param \Zend\EventManager\EventManagerInterface $events
     */
    public function it_should_attach_listeners($events)
    {
        $this->attach($events);
        $callback = Argument::type('array');
        $priority = Argument::type('int');
        $events->attach(CrudEvent::BEFORE_LIST, $callback, $priority)->shouldBeCalled();
        $events->attach(CrudEvent::BEFORE_CREATE, $callback, $priority)->shouldBeCalled();
        $events->attach(CrudEvent::BEFORE_READ, $callback, $priority)->shouldBeCalled();
        $events->attach(CrudEvent::BEFORE_UPDATE, $callback, $priority)->shouldBeCalled();
        $events->attach(CrudEvent::BEFORE_DELETE, $callback, $priority)->shouldBeCalled();
    }

    /**
     * @param \BjyAuthorize\Service\Authorize $authorize
     */
    public function it_should_be_able_to_inject_its_dependencies_as_a_factory($authorize)
    {
        $this->mockListenerFactory($authorize->getWrappedObject());
        $this->getAuthorizationService()->shouldReturn($authorize);
    }

    /**
     * @param \BjyAuthorize\Service\Authorize $authorize
     */
    public function it_should_have_an_authorization_service($authorize)
    {
        $this->mockListenerFactory($authorize->getWrappedObject());
        $this->getAuthorizationService()->shouldReturn($authorize);
    }

    /**
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     */
    public function it_should_throw_exception_when_no_authorization_service_exists($serviceManager)
    {
        $serviceManager->has('BjyAuthorize\Service\Authorize')->willReturn(false);
        $this->shouldThrow('\Phpro\SmartCrud\Exception\SmartCrudException')->duringCreateService($serviceManager);
    }

    /**
     * @param \BjyAuthorize\Service\Authorize                  $authorizeService
     * @param \Zend\Permissions\Acl\Resource\ResourceInterface $entity
     */
    public function it_should_use_ResourceInterface_by_default($authorizeService, $entity)
    {
        $authorizeService->isAllowed(Argument::cetera())->willReturn(true);
        $this->mockListenerFactory($authorizeService->getWrappedObject());

        $permission = 'permission';
        $this->isAllowed($entity, $permission);
        $authorizeService->isAllowed($entity, $permission)->shouldBeCalled();
    }

    /**
     * @param \BjyAuthorize\Service\Authorize $authorizeService
     * @param \stdClass                       $entity
     */
    public function it_should_use_classname_if_entity_is_no_ResourceInterface($authorizeService, $entity)
    {
        $authorizeService->isAllowed(Argument::cetera())->willReturn(true);
        $this->mockListenerFactory($authorizeService->getWrappedObject());

        $className = get_class($entity->getWrappedObject());
        $permission = 'permission';
        $this->isAllowed($entity, $permission);
        $authorizeService->isAllowed($className, $permission)->shouldBeCalled();
    }

    /**
     * @param \stdClass $entity
     */
    public function it_should_throw_bjyAhtorize_unauthorized_exception_on_disallowed_actions($entity)
    {
        $this->mockAuthorizeService(false);
        $permission = 'not-allowed';
        $this->shouldThrow('\BjyAuthorize\Exception\UnAuthorizedException')->duringIsAllowed($entity, $permission);
    }

    /**
     * @param \BjyAuthorize\Service\Authorize                  $authorizeService
     *                                                                           @param \Phpro\SmartCrud\Event\CrudEvent $event
     * @param \Zend\Permissions\Acl\Resource\ResourceInterface $resource
     */
    public function it_should_validate_list_privilege($authorizeService, $event, $resource)
    {
        $this->mockEvent($authorizeService, $event, $resource);

        $this->isListAllowed($event)->shouldReturn(true);
        $authorizeService->isAllowed($resource, BjyAuthorize::PRIVILEGE_LIST)->shouldBeCalled();
    }

    /**
     * @param \BjyAuthorize\Service\Authorize                  $authorizeService
     *                                                                           @param \Phpro\SmartCrud\Event\CrudEvent $event
     * @param \Zend\Permissions\Acl\Resource\ResourceInterface $resource
     */
    public function it_should_validate_create_privilege($authorizeService, $event, $resource)
    {
        $this->mockEvent($authorizeService, $event, $resource);

        $this->isCreateAllowed($event)->shouldReturn(true);
        $authorizeService->isAllowed($resource, BjyAuthorize::PRIVILEGE_CREATE)->shouldBeCalled();
    }

    /**
     * @param \BjyAuthorize\Service\Authorize                  $authorizeService
     *                                                                           @param \Phpro\SmartCrud\Event\CrudEvent $event
     * @param \Zend\Permissions\Acl\Resource\ResourceInterface $resource
     */
    public function it_should_validate_read_privilege($authorizeService, $event, $resource)
    {
        $this->mockEvent($authorizeService, $event, $resource);

        $this->isReadAllowed($event)->shouldReturn(true);
        $authorizeService->isAllowed($resource, BjyAuthorize::PRIVILEGE_READ)->shouldBeCalled();
    }

    /**
     * @param \BjyAuthorize\Service\Authorize                  $authorizeService
     *                                                                           @param \Phpro\SmartCrud\Event\CrudEvent $event
     * @param \Zend\Permissions\Acl\Resource\ResourceInterface $resource
     */
    public function it_should_validate_update_privilege($authorizeService, $event, $resource)
    {
        $this->mockEvent($authorizeService, $event, $resource);

        $this->isUpdateAllowed($event)->shouldReturn(true);
        $authorizeService->isAllowed($resource, BjyAuthorize::PRIVILEGE_UPDATE)->shouldBeCalled();
    }

    /**
     * @param \BjyAuthorize\Service\Authorize                  $authorizeService
     *                                                                           @param \Phpro\SmartCrud\Event\CrudEvent $event
     * @param \Zend\Permissions\Acl\Resource\ResourceInterface $resource
     */
    public function it_should_validate_delete_privilege($authorizeService, $event, $resource)
    {
        $this->mockEvent($authorizeService, $event, $resource);

        $this->isDeleteAllowed($event)->shouldReturn(true);
        $authorizeService->isAllowed($resource, BjyAuthorize::PRIVILEGE_DELETE)->shouldBeCalled();
    }
}
