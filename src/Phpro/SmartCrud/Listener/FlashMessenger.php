<?php

namespace Phpro\SmartCrud\Listener;

use Phpro\SmartCrud\Event\CrudEvent;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class FlashMessenger
 *
 * @package Phpro\SmartCrud
 */
class FlashMessenger extends AbstractListenerAggregate
    implements FactoryInterface
{
    /**
     * Set the priority of the listeners
     */
    const PRIORITY = -9999;

    /**
     * @var \Zend\Mvc\Controller\Plugin\FlashMessenger
     */
    protected $flashMessenger;

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return $this
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $controllerPluginManager = $serviceLocator->get('ControllerPluginManager');
        $this->flashMessenger = $controllerPluginManager->get('flashmessenger');

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(CrudEvent::AFTER_CREATE, array($this, 'createSucceeded'), self::PRIORITY);
        $this->listeners[] = $events->attach(CrudEvent::AFTER_UPDATE, array($this, 'updateSucceeded'), self::PRIORITY);
        $this->listeners[] = $events->attach(CrudEvent::AFTER_DELETE, array($this, 'deleteSucceeded'), self::PRIORITY);

        $this->listeners[] = $events->attach(CrudEvent::INVALID_CREATE, array($this, 'createFailed'), self::PRIORITY);
        $this->listeners[] = $events->attach(CrudEvent::INVALID_UPDATE, array($this, 'updateFailed'), self::PRIORITY);
        $this->listeners[] = $events->attach(CrudEvent::INVALID_DELETE, array($this, 'deleteFailed'), self::PRIORITY);
    }

    /**
     * @return \Zend\Mvc\Controller\Plugin\FlashMessenger
     */
    public function getFlashMessenger()
    {
        return $this->flashMessenger;
    }

    /**
     * @param CrudEvent $e
     */
    public function createSucceeded(CrudEvent $e)
    {
        $this->getFlashMessenger()->addSuccessMessage('Created a new record.');
    }

    /**
     * @param CrudEvent $e
     */
    public function updateSucceeded(CrudEvent $e)
    {
        $this->getFlashMessenger()->addSuccessMessage('Updated the record.');
    }

    /**
     * @param CrudEvent $e
     */
    public function deleteSucceeded(CrudEvent $e)
    {
        $this->getFlashMessenger()->addSuccessMessage('Deleted the record.');
    }

    /**
     * @param CrudEvent $e
     */
    public function createFailed(CrudEvent $e)
    {
        $this->getFlashMessenger()->addErrorMessage('Could not create a new record.');
    }

    /**
     * @param CrudEvent $e
     */
    public function updateFailed(CrudEvent $e)
    {
        $this->getFlashMessenger()->addErrorMessage('Could not update the record.');
    }

    /**
     * @param CrudEvent $e
     */
    public function deleteFailed(CrudEvent $e)
    {
        $this->getFlashMessenger()->addErrorMessage('Could not delete the record.');
    }
}
