<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/veewee/PhproSmartCrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace PhproSmartCrud\Controller;
use PhproSmartCrud\Output\ViewModel;
use PhproSmartCrud\Service\CrudService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Exception;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\Form\Form;

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
     * @return ViewModel
     */
    public function listAction()
    {
        $result = $this->getCrudService()->getList();

        $viewModel = new ViewModel();
        $viewModel->setTemplate('smartcrud/crud/list.phtml');
        $viewModel->setVariable('entities', $result);
        return $result;
    }

    /**
     *
     */
    public function createAction()
    {
        $result = $this->getCrudService()->create();
    }

    /**
     *
     */
    public function updateAction()
    {
        $result = $this->getCrudService()->update();
    }

    /**
     *
     */
    public function readAction()
    {
        $result = $this->getCrudService()->read();
    }

    /**
     *
     */
    public function deleteAction()
    {
        $result = $this->getCrudService()->delete();
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
