<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/veewee/PhproSmartCrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace PhproSmartCrud\View\Strategy;

use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Http\PhpEnvironment\Response;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ModelInterface;

/**
 * Class AbstractStrategy
 *
 * @package PhproSmartCrud\View\Strategy
 */
abstract class AbstractStrategy extends AbstractListenerAggregate
{

    /**
     * @var int
     */
    protected $priority = 0;

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
        $events->attach(MvcEvent::EVENT_RENDER, array($this, 'render'), $this->priority);
    }

    /**
     * @param MvcEvent $e
     *
     * @return Response|void
     */
    public function render(MvcEvent $e)
    {
        // Validate result:
        $result = $e->getResult();
        if ($result instanceof Response) {
            return $result;
        }

        // Validate model
        $model = $e->getViewModel();
        if (!$this->isValidModel($model)) {
            return;
        }

        return $this->renderModel($e, $model);
    }


    /**
     * @param ModelInterface $model
     *
     * @return bool
     */
    abstract protected function isValidModel($model);

    /**
     * @param MvcEvent $e
     * @param ModelInterface $model
     *
     * @return Response|null
     */
    abstract protected function renderModel(MvcEvent $e, ModelInterface $model);

}
