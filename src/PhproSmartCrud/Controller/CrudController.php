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
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\Form\Form;
use Zend\View\Model\ModelInterface;

/**
 * Class CrudController
 */
class CrudController extends AbstractActionController
    implements ServiceManagerAwareInterface
{

    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * @var CrudService
     */
    protected $crudService;

    /**
     * @var Form
     */
    protected $form;

    /**
     * @var mixed
     */
    protected $entity;

    /**
     * Inject parameters from the router directly in the controller
     *
     * @param MvcEvent $e
     *
     * @return mixed
     * @throws \Zend\Mvc\Exception\DomainException
     */
    public function onDispatch(MvcEvent $e)
    {
        $routeMatch = $e->getRouteMatch();
        if (!$routeMatch) {
            throw new Exception\DomainException('Missing route matches; unsure how to retrieve action');
        }

        $id = $routeMatch->getParam('id', null);

        // Add entity
        if ($entityKey = $routeMatch->getParam('entity', false)) {
            $entity = $this->getCrudService()->loadEntity($entityKey, $id);
            $this->setEntity($entity);
        }

        // Add form
        if ($formKey = $routeMatch->getParam('form', false)) {
            $form = $this->getServiceManager()->get($formKey);
            $this->setForm($form);
        }

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
        $action = $action ? $action : $router->getParam('action');
        $models = $router->getParam('output', array());

        // Validate params
        if (!isset($models[$action])) {
            throw new SmartCrudException('No output models are configured for the current route.');
        }

        // Get right model type
        $modelKey = $models[$action];
        $model = $this->getModelType($modelKey);

        // Set model parameters:
        $model->setVariable('result', $result);
        $model->setVariable('form', $this->getForm());
        $model->setVariable('entity', $this->getEntity());
        $model->setTemplate(sprintf('phpro-smartcrud/%s', $router->getParam('action')));

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
            $model = $this->getServiceManager()->get('phpro.smartcrud.view.model.json');
        } else {
            $model = $this->getServiceManager()->get($modelKey);
        }

        return $model;
    }

    /**
     * @param ServiceManager $serviceManager
     *
     * @return $this
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
        return $this;
    }

    /**
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
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
            $crudService = $this->getServiceManager()->get('phpro.smartcrud');
            $crudService
                ->setParameters(array_merge($this->params()->fromQuery(), $this->params()->fromPost()))
                ->setForm($this->getForm())
                ->setEntity($this->getEntity());
            $this->crudService = $crudService;
        }
        return $this->crudService;
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

    /**
     * @param $entity
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

}
