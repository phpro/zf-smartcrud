<?php

namespace PhproSmartCrud\Listener;

use BjyAuthorize\Exception\UnAuthorizedException;
use PhproSmartCrud\Event\CrudEvent;
use PhproSmartCrud\Exception\SmartCrudException;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;

/**
 * Class Authorize
 * This listener will add authorisation before handling the actions.
 *
 * Dependency: bjyAuthorize
 *
 * @package PhproSmartCrud\Listener
 */
class BjyAuthorize extends AbstractListenerAggregate
    implements ServiceManagerAwareInterface
{

    /**
     * Set the priority of the listeners
     */
    const PRIORITY = 9999;

    const PRIVILEGE_LIST = 'list';
    const PRIVILEGE_CREATE = 'create';
    const PRIVILEGE_READ = 'read';
    const PRIVILEGE_UPDATE = 'update';
    const PRIVILEGE_DELETE = 'delete';

    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    /**
     * @return \Zend\ServiceManager\ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * @inheritDoc
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(CrudEvent::BEFORE_LIST, array($this, 'isListAllowed'), self::PRIORITY);
        $this->listeners[] = $events->attach(CrudEvent::BEFORE_CREATE, array($this, 'isCreateAllowed'), self::PRIORITY);
        $this->listeners[] = $events->attach(CrudEvent::BEFORE_READ, array($this, 'isReadAllowed'), self::PRIORITY);
        $this->listeners[] = $events->attach(CrudEvent::BEFORE_UPDATE, array($this, 'isUpdateAllowed'), self::PRIORITY);
        $this->listeners[] = $events->attach(CrudEvent::BEFORE_DELETE, array($this, 'isDeleteAllowed'), self::PRIORITY);
    }

    /**
     * @param CrudEvent $e
     *
     * @return bool
     */
    public function isListAllowed(CrudEvent $e)
    {
        return $this->isAllowed($e->getEntity(), self::PRIVILEGE_LIST);
    }

    /**
     * @param CrudEvent $e
     *
     * @return bool
     */
    public function isCreateAllowed(CrudEvent $e)
    {
        return $this->isAllowed($e->getEntity(), self::PRIVILEGE_CREATE);
    }

    /**
     * @param CrudEvent $e
     *
     * @return bool
     */
    public function isReadAllowed(CrudEvent $e)
    {
        return $this->isAllowed($e->getEntity(), self::PRIVILEGE_READ);
    }

    /**
     * @param CrudEvent $e
     *
     * @return bool
     */
    public function isUpdateAllowed(CrudEvent $e)
    {
        return $this->isAllowed($e->getEntity(), self::PRIVILEGE_UPDATE);
    }

    /**
     * @param CrudEvent $e
     *
     * @return bool
     */
    public function isDeleteAllowed(CrudEvent $e)
    {
        return $this->isAllowed($e->getEntity(), self::PRIVILEGE_DELETE);
    }

    /**
     * Load Authorize service
     *
     * @return \BjyAuthorize\Service\Authorize
     * @throws \PhproSmartCrud\Exception\SmartCrudException
     */
    public function getAuthorizeService()
    {
        $serviceManager = $this->getServiceManager();
        if (!$serviceManager->has('BjyAuthorize\Service\Authorize')) {
            throw new SmartCrudException('The BjyAuthorize listener needs bjyAuthorize module installed');
        }
        return $serviceManager->get('BjyAuthorize\Service\Authorize');
    }

    /**
     * @param $resource
     * @param $privilege
     *
     * @return bool
     * @throws \BjyAuthorize\Exception\UnAuthorizedException
     */
    public function isAllowed($resource, $privilege)
    {
        if (!($resource instanceof ResourceInterface)) {
            $resource = get_class($resource);
        }

        $authorizeService = $this->getAuthorizeService();
        $allowed = $authorizeService->isAllowed($resource, $privilege);

        if (!$allowed) {
            throw new UnAuthorizedException(sprintf('You are unauthorized to %s the current page.', $privilege));
        }
        return $allowed;
    }

}
