<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/phpro/zf-smartcrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Phpro\SmartCrud\Controller;
use Phpro\SmartCrud\Service\SmartServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Phpro\SmartCrud\View\Model\ViewModelBuilder;
use Zend\View\Model\ModelInterface;

/**
 * Class CrudController
 */
class CrudController extends AbstractActionController
    implements CrudControllerInterface
{

    /**
     * @var SmartServiceInterface
     */
    protected $smartService;

    /**
     * @var ViewModelBuilder
     */
    protected $viewModelBuilder;

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
     * @param SmartServiceInterface $service
     *
     * @return $this
     */
    public function setSmartService(SmartServiceInterface $service)
    {
        $this->smartService = $service;

        return $this;
    }

    /**
     * @return SmartServiceInterface
     */
    public function getSmartService()
    {
        return $this->smartService;
    }

    /**
     * @param ViewModelBuilder $viewModelBuilder
     *
     * @return $this
     */
    public function setViewModelBuilder(ViewModelBuilder $viewModelBuilder)
    {
        $this->viewModelBuilder = $viewModelBuilder;

        return $this;
    }

    /**
     * @return \Phpro\SmartCrud\View\Model\ViewModelBuilder
     */
    public function getViewModelBuilder()
    {
        return $this->viewModelBuilder;
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
            && $this->getSmartService()->run(null, $this->getRequest()->getPost())) {
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
            && $this->getSmartService()->run($this->getEntityId(), $this->getRequest()->getPost())) {
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
            && $this->getSmartService()->run($this->getEntityId(), $this->getRequest()->getPost())) {
            return $this->redirect()->toRoute(null, array('action' => 'index'));
        }

        return $this->prepareModel('delete');
    }

    /**
     * @param $action
     *
     * @return \Phpro\SmartCrud\View\Model\JsonModel|\Phpro\SmartCrud\View\Model\ViewModel
     */
    public function prepareModel($action)
    {
//        $this->getSmartService()->setParameters($this->getServiceLocator()->get('Phpro\SmartCrud\Service\ParametersService'));
        $entity = $this->getSmartService()->loadEntity($this->getEntityId());

        return $this->getViewModelBuilder()->build($this->getRequest(), $entity, $this->getSmartService(), $action);
    }

}
