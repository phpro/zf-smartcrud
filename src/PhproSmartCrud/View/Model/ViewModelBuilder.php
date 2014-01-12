<?php

namespace PhproSmartCrud\View\Model;

use PhproSmartCrud\Service\CrudServiceInterface;
use Zend\Http\Request as HttpRequest;
use Zend\View\Model\JsonModel;

/**
 * Class AbstractViewModelFactory
 *
 * @package PhproSmartCrud\View
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
    public function build(HttpRequest $request, CrudServiceInterface $service, $action)
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
