<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/veewee/PhproSmartCrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace PhproSmartCrud\Controller;
use PhproSmartCrud\Gateway\CrudGatewayInterface;
use PhproSmartCrud\Output\ViewModel;
use PhproSmartCrud\Service\CrudService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\Form\Form;

/**
 * Class CrudController
 */
class CrudController extends AbstractActionController implements ServiceManagerAwareInterface
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
     * @var CrudGatewayInterface
     */
    protected $gateway;

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
            $crudService = $this->getServiceManager()->get('PhproSmartCrud\Service\CrudService');
            $crudService
                ->setParameters(array_merge($this->params()->fromQuery(), $this->params()->fromPost()))
                ->setGateway($this->getGateway())
                ->setForm($this->getForm())
                ->setEntity($this->getEntity());
            $this->crudService = $crudService;
        }
        return $this->crudService;
    }

    /**
     * @param \Zend\Form\Form $form
     */
    public function setForm($form)
    {
        $this->form = $form;
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

    /**
     * @param $gateway
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

}
