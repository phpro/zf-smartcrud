<?php

namespace spec\PhproSmartCrud\Listener;

use PhpSpec\ObjectBehavior;
use PhproSmartCrud\Event\CrudEvent;
use PhproSmartCrud\Listener\BjyAuthorize;
use Prophecy\Argument;
use Prophecy\Prophet;

class BjyAuthorizeSpec extends AbstractListenerSpec
{

    /**
     * @param \BjyAuthorize\Service\Authorize $authorizeService
     */
    protected function mockServiceManager($authorizeService)
    {
        $prophet = new Prophet();
        $serviceManager = $prophet->prophesize('\Zend\ServiceManager\ServiceManager');

        $this->setServiceManager($serviceManager);
        $serviceManager->has('BjyAuthorize\Service\Authorize')->willReturn(true);
        $serviceManager->get('BjyAuthorize\Service\Authorize')->willReturn($authorizeService);
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

        $this->mockServiceManager($authorizeService->reveal());
    }

    /**
     * @param \PhproSmartCrud\Event\CrudEvent $event
     * @param \BjyAuthorize\Service\Authorize $authorizeService
     * @param \Zend\Permissions\Acl\Resource\ResourceInterface $resource
     */
    protected function mockEvent($authorizeService, $event, $resource)
    {
        $authorizeService->isAllowed(Argument::cetera())->willReturn(true);
        $this->mockServiceManager($authorizeService->getWrappedObject());
        $event->getEntity()->willReturn($resource->getWrappedObject());
    }

    public function let()
    {
        $this->mockAuthorizeService(true);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('PhproSmartCrud\Listener\BjyAuthorize');
    }

    public function it_should_extend_abstract_listener()
    {
        $this->shouldBeAnInstanceOf('PhproSmartCrud\Listener\AbstractListener');
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
     * @param \Zend\EventManager\EventManagerInterface $events
     *
     * @TODO find a way to count the amount of attached listeners without hardcoding it
     */
    public function it_should_detach_all_listeners($events)
    {
        $this->attach($events);
        $this->detach($events);
        $events->detach(Argument::any())->shouldBeCalledTimes(5);
    }

    /**
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     * @param \BjyAuthorize\Service\Authorize $authorize
     */
    public function it_should_have_an_authorization_service($serviceManager, $authorize)
    {
        $this->setServiceManager($serviceManager);
        $serviceManager->has('BjyAuthorize\Service\Authorize')->willReturn(true);
        $serviceManager->get('BjyAuthorize\Service\Authorize')->willReturn($authorize);

        $this->getAuthorizeService()->shouldReturn($authorize);
    }

    /**
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     */
    public function it_should_throw_exception_when_no_authorization_service_exists($serviceManager)
    {
        $this->setServiceManager($serviceManager);
        $serviceManager->has('BjyAuthorize\Service\Authorize')->willReturn(false);

        $this->shouldThrow('\PhproSmartCrud\Exception\SmartCrudException')->duringGetAuthorizeService();
    }

    /**
     * @param \BjyAuthorize\Service\Authorize $authorizeService
     * @param \Zend\Permissions\Acl\Resource\ResourceInterface $entity
     */
    public function it_should_use_ResourceInterface_by_default($authorizeService, $entity)
    {
        $authorizeService->isAllowed(Argument::cetera())->willReturn(true);
        $this->mockServiceManager($authorizeService->getWrappedObject());

        $permission = 'permission';
        $this->isAllowed($entity, $permission);
        $authorizeService->isAllowed($entity, $permission)->shouldBeCalled();
    }

    /**
     * @param \BjyAuthorize\Service\Authorize $authorizeService
     * @param \stdClass $entity
     */
    public function it_should_use_classname_if_entity_is_no_ResourceInterface($authorizeService, $entity)
    {
        $authorizeService->isAllowed(Argument::cetera())->willReturn(true);
        $this->mockServiceManager($authorizeService->getWrappedObject());

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
     * @param \BjyAuthorize\Service\Authorize $authorizeService
     *  @param \PhproSmartCrud\Event\CrudEvent $event
     * @param \Zend\Permissions\Acl\Resource\ResourceInterface $resource
     */
    public function it_should_validate_list_privilege($authorizeService, $event, $resource)
    {
        $this->mockEvent($authorizeService, $event, $resource);

        $this->isListAllowed($event)->shouldReturn(true);
        $authorizeService->isAllowed($resource, BjyAuthorize::PRIVILEGE_LIST)->shouldBeCalled();
    }

    /**
     * @param \BjyAuthorize\Service\Authorize $authorizeService
     *  @param \PhproSmartCrud\Event\CrudEvent $event
     * @param \Zend\Permissions\Acl\Resource\ResourceInterface $resource
     */
    public function it_should_validate_create_privilege($authorizeService, $event, $resource)
    {
        $this->mockEvent($authorizeService, $event, $resource);

        $this->isCreateAllowed($event)->shouldReturn(true);
        $authorizeService->isAllowed($resource, BjyAuthorize::PRIVILEGE_CREATE)->shouldBeCalled();
    }

    /**
     * @param \BjyAuthorize\Service\Authorize $authorizeService
     *  @param \PhproSmartCrud\Event\CrudEvent $event
     * @param \Zend\Permissions\Acl\Resource\ResourceInterface $resource
     */
    public function it_should_validate_read_privilege($authorizeService, $event, $resource)
    {
        $this->mockEvent($authorizeService, $event, $resource);

        $this->isReadAllowed($event)->shouldReturn(true);
        $authorizeService->isAllowed($resource, BjyAuthorize::PRIVILEGE_READ)->shouldBeCalled();
    }

    /**
     * @param \BjyAuthorize\Service\Authorize $authorizeService
     *  @param \PhproSmartCrud\Event\CrudEvent $event
     * @param \Zend\Permissions\Acl\Resource\ResourceInterface $resource
     */
    public function it_should_validate_update_privilege($authorizeService, $event, $resource)
    {
        $this->mockEvent($authorizeService, $event, $resource);

        $this->isUpdateAllowed($event)->shouldReturn(true);
        $authorizeService->isAllowed($resource, BjyAuthorize::PRIVILEGE_UPDATE)->shouldBeCalled();
    }

    /**
     * @param \BjyAuthorize\Service\Authorize $authorizeService
     *  @param \PhproSmartCrud\Event\CrudEvent $event
     * @param \Zend\Permissions\Acl\Resource\ResourceInterface $resource
     */
    public function it_should_validate_delete_privilege($authorizeService, $event, $resource)
    {
        $this->mockEvent($authorizeService, $event, $resource);

        $this->isDeleteAllowed($event)->shouldReturn(true);
        $authorizeService->isAllowed($resource, BjyAuthorize::PRIVILEGE_DELETE)->shouldBeCalled();
    }
}
