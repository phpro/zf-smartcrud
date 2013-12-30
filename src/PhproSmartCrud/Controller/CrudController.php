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
use PhproSmartCrud\Service\AbstractCrudService;
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
    protected $smartService;

    /**
     * Name of request or query parameter containing identifier
     *
     * @var string
     */
    protected $identifierName = 'id';

    /**
     * Set the route match/query parameter name containing the identifier
     *
     * @param  string $name
     * @return self
     */
    public function setIdentifierName($name)
    {
        $this->identifierName = (string) $name;
        return $this;
    }

    /**
     * Retrieve the route match/query parameter name containing the identifier
     *
     * @return string
     */
    public function getIdentifierName()
    {
        return $this->identifierName;
    }

    /**
     * Returns the id specified by the identifier name
     *
     * @return mixed
     */
    public function getEntityId()
    {
        return $this->params()->fromRoute($this->getIdentifierName(), null);
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
        $this->setIdentifierName($routeMatch->getParam('identifier-name', 'id'));

        $action = $routeMatch->getParam('action', 'not-found');
        $serviceKey = $routeMatch->getParam('smart-service', null);
        $this->setSmartService($this->getServiceLocator()->get($serviceKey . '::' . $action));

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
            && $this->getSmartService()->create($this->getRequest()->getPost())) {
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
            && $this->getSmartService()->update($this->getEntityId(), $this->getRequest()->getPost())) {
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
            && $this->getSmartService()->delete($this->getEntityId(), $this->getRequest()->getPost())) {
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
        $service = $this->getSmartService();
        if ($this->getRequest()->isXmlHttpRequest()) {
            $model = $this->getServiceLocator()->get('PhproSmartCrud\View\Model\JsonModel');
            $service->setOutputModel($model);
        }
        $model = $service->getOutputModel();
        $model->setTemplate(sprintf('phpro-smartcrud/%s', $action));
        if($this->getRequest()->isXmlHttpRequest()) {
            $model->setTerminal(true);
        }
        return $model;
    }

    /**
     * @param AbstractCrudService $service
     *
     * @return $this
     */
    public function setSmartService(AbstractCrudService $service)
    {
        $this->smartService = $service;
        return $this;
    }

    /**
     * @return AbstractCrudService
     */
    public function getSmartService()
    {
        return $this->smartService;
    }
}
