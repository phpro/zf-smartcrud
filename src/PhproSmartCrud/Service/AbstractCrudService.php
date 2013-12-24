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
use PhproSmartCrud\Gateway\CrudGatewayInterface;
use Zend\EventManager\EventManager;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

/**
 * Class AbstractCrudService
 *
 * @package PhproSmartCrud\Service
 */
abstract class AbstractCrudService implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    /**
     * @var ParametersService
     */
    protected $parameters;

    /**
     * @var CrudGatewayInterface
     */
    protected $gateway;

    /**
     * @var mixed
     */
    protected $entity;

    /**
     * @var \Zend\EventManager\Event
     */
    protected $eventManager;

    /**
     * @var Form
     */
    protected $form;

    /**
     * @var string
     */
    protected $entityKey;

    /**
     * @var string
     */
    protected $formKey;

    /**
     * @param string $formKey
     */
    public function setFormKey($formKey)
    {
        $this->formKey = $formKey;
        return $this;
    }

    /**
     * @return string
     */
    public function getFormKey()
    {
        return $this->formKey;
    }

    /**
     * @param string $entityKey
     */
    public function setEntityKey($entityKey)
    {
        $this->entityKey = $entityKey;
        return $this;
    }

    /**
     * @return string
     */
    public function getEntityKey()
    {
        return $this->entityKey;
    }
    /**
     * @param mixed $entity
     *
     * @return $this
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param      $entityKey
     * @param null $id
     *
     * @return mixed
     */
    public function loadEntity($id = null)
    {
        return $this->getGateway()->loadEntity($this->getEntityKey(), $id);
    }

    /**
     * @param \Zend\EventManager\EventManager $eventManager $eventManager
     *
     * @return $this
     */
    public function setEventManager($eventManager)
    {
        $this->eventManager = $eventManager;
        return $this;
    }

    /**
     * @return \Zend\EventManager\EventManager
     */
    public function getEventManager()
    {
        if (!$this->eventManager) {
            $this->eventManager = new EventManager();
        }
        return $this->eventManager;
    }

    /**
     * @param \PhproSmartCrud\Gateway\CrudGatewayInterface $gateway
     *
     * @return $this
     */
    public function setGateway($gateway)
    {
        $this->gateway = $gateway;
        return $this;
    }

    /**
     * @return \PhproSmartCrud\Gateway\CrudGatewayInterface
     */
    public function getGateway()
    {
        return $this->gateway;
    }

    /**
     * @param array $parameters
     *
     * @return ParametersService
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;
        return $this;
    }

    /**
     * @return ParametersService
     */
    public function getParameters()
    {
        return $this->parameters;
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
    public function getForm($entity)
    {
        if (empty($this->form)) {
            /** @var \Zend\Form\Form $form */
            $this->form = $this->getServiceLocator()->get($this->getFormKey());
        }
        $this->form->bind($entity);
        $this->form->bindOnValidate();
        $this->getEventManager()->trigger($this->createEvent(CrudEvent::FORM_READY, $this->form));
        return $this->form;
    }

    /**
     * @param $eventName
     *
     * @return CrudEvent
     */
    public function createEvent($eventName, $target)
    {
        $event = new CrudEvent($eventName, $target, $this->getParameters());
        return $event;
    }

}
