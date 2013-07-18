<?php

namespace PhproSmartCrud\Listener;

use PhproSmartCrud\Event\CrudEvent;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;

/**
 * Class FlashMessenger
 *
 * @package PhproSmartCrud
 */
class FlashMessenger
    implements  ListenerAggregateInterface, ServiceManagerAwareInterface
{

    /**
     * @var array
     */
    protected $listeners = array();

    /**
     * @var \Zend\Mvc\Controller\Plugin\FlashMessenger
     */
    protected $flashMessenger;

    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * @inheritDoc
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(CrudEvent::AFTER_CREATE, array($this, 'createSucceeded'));
        $this->listeners[] = $events->attach(CrudEvent::AFTER_UPDATE, array($this, 'updateSucceeded'));
        $this->listeners[] = $events->attach(CrudEvent::AFTER_DELETE, array($this, 'deleteSucceeded'));

        $this->listeners[] = $events->attach(CrudEvent::INVALID_CREATE, array($this, 'createFailed'));
        $this->listeners[] = $events->attach(CrudEvent::INVALID_UPDATE, array($this, 'updateFailed'));
        $this->listeners[] = $events->attach(CrudEvent::INVALID_DELETE, array($this, 'deleteFailed'));
    }

    /**
     * @inheritDoc
     */
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $listener) {
            $events->detach($listener);
        }
    }

    /**
     * @inheritDoc
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
        return $this;
    }

    /**
     * @return \Zend\ServiceManager\ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * @return \Zend\Mvc\Controller\Plugin\FlashMessenger
     */
    public function getFlashMessenger()
    {
        if (!$this->flashMessenger) {
            $controllerPluginManager = $this->getServiceManager()->get('ControllerPluginManager');
            $this->flashMessenger = $controllerPluginManager->get('flashmessenger');
        }

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