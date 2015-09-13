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
        $result = $this->getSmartService()->run($this->getEntityId(), $this->getRequest()->getQuery());
        return $this->getViewModelBuilder()->build($this->getRequest(), $result, 'list');
    }

    /**
     * @return ModelInterface
     */
    public function createAction()
    {
        $data = $this->getRequest()->isPost() ? $this->getRequest()->getPost() : null;
        $result = $this->getSmartService()->run(null, $data);
        if ($this->getRequest()->isPost() && $result->isSuccessFull()) {
            return $this->redirect()->toRoute(null, array('action' => 'list'), true);
        }
        return $this->getViewModelBuilder()->build($this->getRequest(), $result, 'create');
    }

    /**
     * @return ModelInterface
     */
    public function updateAction()
    {
        $data = $this->getRequest()->isPost() ? $this->getRequest()->getPost() : null;
        $result = $this->getSmartService()->run($this->getEntityId(), $data);
        if ($this->getRequest()->isPost() && $result->isSuccessFull()) {
            return $this->redirect()->toRoute(null, array('action' => 'update', $this->getIdentifierName() => $this->getEntityId()), true);
        }
        return $this->getViewModelBuilder()->build($this->getRequest(), $result, 'update');
    }

    /**
     * @return ModelInterface
     */
    public function readAction()
    {
        $result = $this->getSmartService()->run($this->getEntityId(), array());
        return $this->getViewModelBuilder()->build($this->getRequest(), $result, 'read');
    }

    /**
     * @return ModelInterface
     */
    public function deleteAction()
    {
        $request = $this->getRequest();
        $data = $request->isPost() ? $request->getPost() : null;
        $result = $this->getSmartService()->run($this->getEntityId(), $data);
        if (($request->isPost() && !$request->isXmlHttpRequest()) && $result->isSuccessFull()) {
            return $this->redirect()->toRoute(null, array('action' => 'list'), true);
        }
        return $this->getViewModelBuilder()->build($this->getRequest(), $result, 'delete');
    }
}
