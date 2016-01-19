<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/phpro/zf-smartcrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Phpro\SmartCrud\Service;

use Phpro\SmartCrud\Event\CrudEvent;
use Phpro\SmartCrud\Gateway\CrudGatewayInterface;
use Zend\EventManager\EventManager;

/**
 * Class AbstractSmartService
 *
 * @package Phpro\SmartCrud\Service
 */
abstract class AbstractSmartService implements SmartServiceInterface
{
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
     * @var \Zend\Form\Form
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
     * @var array
     */
    protected $options = array();

    /**
     * @var SmartServiceResult
     */
    private $result = null;

    public function setResult(SmartServiceResult $result)
    {
        $this->result = $result;
    }

    /**
     * @return SmartServiceResult
     */
    protected function getResult($id = null)
    {
        if ($this->result) {
            return $this->result;
        }
        return $this->result ? $this->result : new SmartServiceResult();
    }

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
     * @param \Phpro\SmartCrud\Gateway\CrudGatewayInterface $gateway
     *
     * @return $this
     */
    public function setGateway($gateway)
    {
        $this->gateway = $gateway;

        return $this;
    }

    /**
     * @return \Phpro\SmartCrud\Gateway\CrudGatewayInterface
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
        if ($this->form->hasValidated()) {
            return $this->form;
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
    public function createEvent($eventName, $target, $params = null)
    {
        $event = new CrudEvent($eventName, $target, $params);

        return $event;
    }

    /**
     * @param array $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }
}
