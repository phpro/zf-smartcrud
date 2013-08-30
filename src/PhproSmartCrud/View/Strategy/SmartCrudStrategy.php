<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/veewee/PhproSmartCrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace PhproSmartCrud\View\Strategy;

use PhproSmartCrud\View\Model\JsonModel;
use PhproSmartCrud\View\Model\RedirectModel;
use PhproSmartCrud\View\Model\ViewModel;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;
use Zend\Stdlib\ResponseInterface as Response;

/**
 * Class SmartCrudStrategy
 *
 * @package PhproSmartCrud\View\Strategy
 */
class SmartCrudStrategy extends AbstractListenerAggregate
{

    /**
     * Add event before the default rendering strategy is called
     */
    const EVENT_PRIORITY = -9000;

    /**
     * Attach one or more listeners
     *
     * Implementors may add an optional $priority argument; the EventManager
     * implementation will pass this to the aggregate.
     *
     * @param EventManagerInterface $events
     *
     * @return void
     */
    public function attach(EventManagerInterface $events)
    {
        $events->attach(MvcEvent::EVENT_RENDER, array($this, 'render'), self::EVENT_PRIORITY);
    }

    /**
     * @param MvcEvent $e
     *
     * @return Response
     */
    public function render(MvcEvent $e)
    {
        $result = $e->getResult();
        $model = $e->getViewModel();
        if ($result instanceof Response) {
            return $result;
        }

        if ($model instanceof ViewModel) {
            return $this->renderViewModel($e, $model);
        }

        if ($model instanceof JsonModel) {
            return $this->renderJsonModel($e, $model);
        }

        if ($model instanceof RedirectModel) {
            return $this->renderRedirectModel($e, $model);
        }
    }

    /**
     * @param MvcEvent  $e
     * @param ViewModel $model
     *
     * @return Response
     */
    protected  function renderViewModel(MvcEvent $e, ViewModel $model)
    {
        $request   = $e->getRequest();
        $response  = $e->getResponse();
        return $response;
    }

    /**
     * @param MvcEvent  $e
     * @param JsonModel $model
     *
     * @return Response
     */
    protected function renderJsonModel(MvcEvent $e, JsonModel $model)
    {
        $request   = $e->getRequest();
        $response  = $e->getResponse();
        return $response;
    }

    /**
     * @param MvcEvent      $e
     * @param RedirectModel $model
     *
     * @return Response
     */
    protected function renderRedirectModel(MvcEvent $e, RedirectModel $model)
    {
        $request   = $e->getRequest();
        $response  = $e->getResponse();
        return $response;
    }

}