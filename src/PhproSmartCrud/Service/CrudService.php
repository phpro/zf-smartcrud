<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/veewee/PhproSmartCrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace PhproSmartCrud\Service;

use PhproSmartCrud\Event\CrudEvent;
use PhproSmartCrud\Exception\SmartCrudException;
use PhproSmartCrud\Gateway\CrudGatewayInterface;
use Zend\EventManager\EventManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\Form\Form;

/**
 * Class CrudService
 *
 * @package PhproSmartCrud\Service
 */
class CrudService extends AbstractCrudService
    implements ServiceManagerAwareInterface
{

    /**
     * @var \Zend\ServiceManager\ServiceManager
     */
    protected $serviceManager;

    /**
     * @var Form
     */
    protected $form;

    /**
     * @return array|\Traversable
     */
    public function getList()
    {
        $service = $this->getActionService('PhproSmartCrud\Service\ListService');
        return $service->getList();
    }

    /**
     * @return bool
     */
    public function create()
    {
        if (!$this->isValid()) {
            return false;
        }

        $service = $this->getActionService('PhproSmartCrud\Service\CreateService');
        return $service->create();
    }

    /**
     * @return mixed
     */
    public function read()
    {
        $service = $this->getActionService('PhproSmartCrud\Service\ReadService');
        return $service->read();
    }

    /**
     * @return bool
     */
    public function update()
    {
        if (!$this->isValid()) {
            return false;
        }

        $service = $this->getActionService('PhproSmartCrud\Service\UpdateService');
        return $service->update();
    }

    /**
     * @return bool
     */
    public function delete()
    {
        $service = $this->getActionService('PhproSmartCrud\Service\DeleteService');
        return $service->delete();
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        $valid = $this->triggerValidationEvent(CrudEvent::BEFORE_VALIDATE);
        if (!$valid) {
            return false;
        }

        $this->getForm()->setData($this->getParameters());
        $valid = $this->getForm()->isValid();
        if (!$valid) {
            return false;
        }

        $valid = $this->triggerValidationEvent(CrudEvent::AFTER_VALIDATE);
        if (!$valid) {
            return false;
        }

        return true;
    }

    /**
     * @param $eventName
     *
     * @return bool
     */
    protected function triggerValidationEvent($eventName)
    {
        $eventManager = $this->getEventManager();
        $event = $this->createEvent($eventName);
        $results = $eventManager->trigger($event, null, array(), function ($valid) {
            return !$valid;
        });

        if($results->stopped()) {
            return false;
        }
        return true;
    }

    /**
     * @param $actionService
     *
     * @return AbstractCrudService
     * @throws SmartCrudException
     */
    public function getActionService($actionService)
    {
        if (!$this->getServiceManager()->has($actionService)) {
            throw new SmartCrudException('Invalid crud action service: ' . $actionService);
        }

        /** @var AbstractCrudService $service  */
        $service =  $this->getServiceManager()->get($actionService);
        if (!($service instanceof AbstractCrudService)) {
            throw new SmartCrudException('Invalid crud action service: ' . $actionService);
        }

        $service
            ->setEventManager($this->getEventManager())
            ->setEntity($this->getEntity())
            ->setParameters($this->getParameters())
            ->setGateway($this->getGateway());
        return $service;
    }

    /**
     * @param $serviceManager
     *
     * @return $this
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
     * @param \Zend\Form\Form $form
     *
     * @return $this
     */
    public function setForm($form)
    {
        $this->form = $form;
        return $this;
    }

    /**
     * @return \Zend\Form\Form
     */
    public function getForm()
    {
        return $this->form;
    }

}