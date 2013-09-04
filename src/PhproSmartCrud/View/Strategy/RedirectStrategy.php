<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/veewee/PhproSmartCrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace PhproSmartCrud\View\Strategy;

use PhproSmartCrud\View\Model\RedirectModel;
use Zend\Http\PhpEnvironment\Response;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ModelInterface;

/**
 * Class RedirectStrategy
 *
 * @package PhproSmartCrud\View\Strategy
 */
class RedirectStrategy extends AbstractStrategy
{
    /**
     * @var int
     */
    protected $priority = 10000;

    /**
     * @param ModelInterface $model
     *
     * @return bool
     */
    protected function isValidModel($model)
    {
        return ($model instanceof RedirectModel);
    }

    /**
     * @param MvcEvent       $e
     * @param RedirectModel $model
     *
     * @return Response
     */
    protected function renderModel(MvcEvent $e, ModelInterface $model)
    {
        /** @var \Zend\Http\PhpEnvironment\Response $response  */
        $response  = $e->getResponse();
        $headers = $response->getHeaders();

        // set redirect:
        $redirect = '/';    // TODO: find the best way to add the route to model

        // Set response
        $headers->clearHeaders();
        $headers->addHeaderLine(sprintf('Location', $redirect));
        $response->setContent(null);

        return $response;
    }

}
