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
use PhproSmartCrud\Service\CrudService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Exception;
use Zend\Form\Form;
use Zend\View\Model\ModelInterface;

/**
 * Class CrudController
 */
class CrudController extends AbstractActionController
{

    /**
     * @var CrudService
     */
    protected $crudService;

    /**
     * @var mixed
     */
    protected $entity;

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
            throw new SmartCrudException('There was no entity type configured to the router');
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

        $identifierName = $routeMatch->getParam('identifier-name', 'id');

        $this->setEntityId($routeMatch->getParam($identifierName, null));
        $this->setEntityKey($routeMatch->getParam('entity', false));
        $this->setFormKey($routeMatch->getParam('form', false));

        // Add listeners
        $routeListeners = $routeMatch->getParam('listeners', array());
        foreach ($routeListeners as $listener) {
            if (!$this->getServiceLocator()->has($listener)) {
                throw new SmartCrudException(sprintf('The route listener class %s could not be found', $listener));
            }
            $eventListener = $this->getServiceLocator()->get($listener);
            $this->getCrudService()->getEventManager()->attach($eventListener);
        }

        // Add entity
        $this->getCrudService()->setEntityKey($this->getEntityKey());
        // Add form
        $this->getCrudService()->setFormKey($this->getFormKey());

        return parent::onDispatch($e);
    }


    /**
     * @return ModelInterface
     */
    public function listAction()
    {
        $result = $this->getCrudService()->getList();
        return $this->createModel($result);
    }

    /**
     * @return ModelInterface
     */
    public function createAction()
    {
        if (!$this->getRequest()->isPost()) {
            return $this->createModel(null);
        }

        $result = $this->getCrudService()->create();
        return $this->createModel($result, 'post-create');
    }

    /**
     * @return ModelInterface
     */
    public function updateAction()
    {
        if (!$this->getRequest()->isPost()) {
            return $this->createModel(null);
        }

        $result = $this->getCrudService()->update();
        return $this->createModel($result, 'post-update');
    }

    /**
     * @return ModelInterface
     */
    public function readAction()
    {
        $result = $this->getCrudService()->read();
        return $this->createModel($result);
    }

    /**
     * @return ModelInterface
     */
    public function deleteAction()
    {
        $result = $this->getCrudService()->delete();
        return $this->createModel($result);
    }

    /**
     * @param mixed $result
     * @param string $action
     *
     * @return ModelInterface
     * @throws \PhproSmartCrud\Exception\SmartCrudException
     */
    protected function createModel($result ,$action = null)
    {
        $event = $this->getEvent();
        $router = $event->getRouteMatch();
        $controllerAction = $router->getParam('action');
        $action = $action ? $action : $controllerAction;
        $models = $router->getParam('output', array());

        // Validate params
        if (!isset($models[$action])) {
            throw new SmartCrudException('No output models are configured for the current route.');
        }

        // Get right model type
        $modelKey = $models[$action];
        $model = $this->getModelType($modelKey);

        // Set model parameters:
        $model->setVariable('action', $controllerAction);
        $model->setVariable('result', $result);
        $model->setVariable('form',  $this->getCrudService()->getForm($this->getEntityId()));
        $model->setVariable('entity', $this->getCrudService()->loadEntity($this->getEntityId()));
        $model->setTemplate(sprintf('phpro-smartcrud/%s', $controllerAction));

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
     * @param $crudService
     *
     * @return $this
     */
    public function setCrudService($crudService)
    {
        $this->crudService = $crudService;
        return $this;
    }

    /**
     * @return \PhproSmartCrud\Service\CrudService
     */
    public function getCrudService()
    {
        if (!$this->crudService) {
            /** @var \PhproSmartCrud\Service\CrudService $crudService  */
            $crudService = $this->getServiceLocator()->get('PhproSmartCrud\Service\CrudServiceFactory');
            $this->crudService = $crudService;
        }
        return $this->crudService;
    }

}
