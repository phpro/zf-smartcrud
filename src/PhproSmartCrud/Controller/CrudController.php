<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/veewee/PhproSmartCrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace PhproSmartCrud\Controller;
use PhproSmartCrud\Exception\SmartCrudException;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Exception;
use Zend\View\Model\ModelInterface;

/**
 * Class CrudController
 */
class CrudController extends AbstractActionController
{

    /**
     * @var AbstractCrudService
     */
    protected $actionService;

    /**
     * @var mixed
     */
    protected $entityId;

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
    protected $actionServiceConfiguration = null;

    /**
     * @param null $actionServiceConfiguration
     */
    public function setActionServiceConfiguration($actionServiceConfiguration)
    {
        if($actionServiceConfiguration == null) {
            throw new SmartCrudException(sprintf('No configuration found for action %s'));
        }
        $this->actionServiceConfiguration = $actionServiceConfiguration;

        return $this;
    }

    /**
     * @return null
     */
    public function getActionServiceConfiguration()
    {
        return $this->actionServiceConfiguration;
    }

    /**
     * @param mixed $entityId
     */
    public function setEntityId($entityId)
    {
        $this->entityId = $entityId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEntityId()
    {
        return $this->entityId;
    }

    /**
     * @param string $formKey
     */
    public function setFormKey($formKey)
    {
        if (!$formKey) {
            throw new SmartCrudException('There was no form configured to the router');
        }
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
        if (!$entityKey) {
            throw new SmartCrudException('There was no entity key configured to the router');
        }
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
     * Inject parameters from the router directly in the controller
     *
     * @param MvcEvent $e
     *
     * @return mixed
     * @throws \Zend\Mvc\Exception\DomainException
     * @throws SmartCrudException
     */
    public function onDispatch(MvcEvent $e)
    {
        $routeMatch = $e->getRouteMatch();
        if (!$routeMatch) {
            throw new Exception\DomainException('Missing route matches; unsure how to retrieve action');
        }

        $action = $routeMatch->getParam('action', 'not-found');
        $this->setActionServiceConfiguration($routeMatch->getParam($action, null));

        $identifierName = $routeMatch->getParam('identifier-name', 'id');
        $this->setEntityId($routeMatch->getParam($identifierName, null));

        $this->setEntityKey($routeMatch->getParam('entity', false));
        $this->setFormKey($routeMatch->getParam('form', false));

        return parent::onDispatch($e);
    }


    /**
     * @return ModelInterface
     */
    public function listAction()
    {
        return $this->prepareModel('list');
    }

    /**
     * @return ModelInterface
     */
    public function createAction()
    {
        if ($this->getRequest()->isPost()
            && $this->getActionService()->create($this->getRequest()->getPost())) {
                return $this->redirect()->toRoute(null, array('action' => 'index'));
        }
        return $this->prepareModel('create');
    }

    /**
     * @return ModelInterface
     */
    public function updateAction()
    {
        if ($this->getRequest()->isPost()
            && $this->getActionService()->update($this->getEntityId(), $this->getRequest()->getPost())) {
            return $this->redirect()->toRoute(null, array('action' => 'view'));
        }
        return $this->prepareModel('update');
    }

    /**
     * @return ModelInterface
     */
    public function readAction()
    {
        return $this->prepareModel('read');
    }

    /**
     * @return ModelInterface
     */
    public function deleteAction()
    {
        if ($this->getRequest()->isPost()
            && $this->getActionService()->delete($this->getEntityId(), $this->getRequest()->getPost())) {
            return $this->redirect()->toRoute(null, array('action' => 'index'));
        }
        return $this->prepareModel('delete');
    }

    /**
     * @param $action
     *
     * @return \PhproSmartCrud\View\Model\JsonModel|\PhproSmartCrud\View\Model\ViewModel
     */
    public function prepareModel($action)
    {
        $service = $this->getActionService();
        $config = $this->getActionServiceConfiguration();

        $model = $this->getModelType($config['output-model']);
        if($this->getRequest()->isXmlHttpRequest()) {
            $model->setVariable('action', $action);
            $model->setVariable('result', $service);
            $model->setTerminal(true);
        } else {
            $entity = $service->loadEntity($this->getEntityId());
            $model->setVariable('action', $action);
            $model->setVariable('result', $service);
            $model->setVariable('form',  $service->getForm($entity));
            $model->setVariable('entity',$entity);
            $model->setTemplate(sprintf('phpro-smartcrud/%s', $action));
        }
        $this->getEventManager()->trigger('view-model-ready-for-dispatch', $model, $service);

        return $model;
    }

    /**
     * @param $modelKey
     *
     * @return ModelInterface
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    protected function getModelType($modelKey)
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $model = $this->getServiceLocator()->get('PhproSmartCrud\View\Model\JsonModel');
        } else {
            $model = $this->getServiceLocator()->get($modelKey);
        }

        return $model;
    }

    /**
     * @return null
     */
    public function getActionService()
    {
        $config = $this->getActionServiceConfiguration();
        $service = $this->getServiceLocator()->get($config['service']);
        $service->setEntityKey($this->getEntityKey());
        $service->setFormKey($this->getFormKey());
        if(array_key_exists('listeners', $config)) {
            foreach ($config['listeners'] as $listener) {
                if (!$this->getServiceLocator()->has($listener)) {
                    throw new SmartCrudException(sprintf('The listener class %s could not be found', $listener));
                }
                $eventListener = $this->getServiceLocator()->get($listener);
                $service->getEventManager()->attach($eventListener);
            }
        }

        return $service;
    }
}
