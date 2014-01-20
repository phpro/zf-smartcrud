<?php

namespace Phpro\SmartCrud\View\Model;

use Phpro\SmartCrud\Service\SmartServiceResult;
use Zend\Http\Request as HttpRequest;
use Zend\View\Model\JsonModel;

/**
 * Class AbstractViewModelFactory
 *
 * @package Phpro\SmartCrud\View
 */
class ViewModelBuilder
{

    /** @var string  */
    private $template = 'phpro-smartcrud/%s';

    /**
     * @param string $template
     *
     * @return $this
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param HttpRequest $request
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function build(HttpRequest $request, SmartServiceResult $result, $action)
    {
        $viewModel = null;
        if ($request->isXmlHttpRequest()) {
            $viewModel = new JsonModel();
            $viewModel->setTerminal(true);
        } else {
            $viewModel = new \Zend\View\Model\ViewModel();
            $viewModel->setVariable('entity', $result->getEntity());
            $viewModel->setVariable('form', $result->getForm());
            $viewModel->setTemplate(sprintf($this->getTemplate(), $action));
        }

        return $viewModel;
    }
}
