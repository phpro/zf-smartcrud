<?php

namespace Phpro\SmartCrud\View\Model;

use Phpro\SmartCrud\Service\SmartServiceInterface;
use Zend\Http\Request as HttpRequest;
use Zend\View\Model\JsonModel;

/**
 * Class AbstractViewModelFactory
 *
 * @package Phpro\SmartCrud\View
 */
class ViewModelBuilder
{
    /**
     * @return \Zend\View\Model\ViewModel
     */
    /**
     * @param HttpRequest $request
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function build(HttpRequest $request, SmartServiceInterface $service, $action)
    {
        $viewModel = null;
        if ($request->isXmlHttpRequest()) {
            $viewModel = new JsonModel();
            $viewModel->setTerminal(true);
        } else {
            $viewModel = new \Zend\View\Model\ViewModel();
            $viewModel->setVariable('service', $service);
            $viewModel->setTemplate(sprintf('phpro-smartcrud/%s', $action));
        }

        return $viewModel;
    }
}
