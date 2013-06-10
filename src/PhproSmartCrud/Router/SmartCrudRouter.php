<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/veewee/PhproSmartCrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace PhproSmartCrud\Router;

use Zend\Stdlib\RequestInterface as Request;
use Zend\Mvc\Router\Http\RouteInterface;
use Zend\Mvc\Router\Http\RouteMatch;

/**
 * Class SmartCrudRouter
 *
 * @package PhproSmartCrud\Router
 */
class SmartCrudRouter implements RouteInterface
{
    /**
     * Create a new route with given options.
     *
     * @param  array|\Traversable $options
     *
     * @return void
     */
    public static function factory($options = array())
    {
        // TODO: Implement factory() method.
    }

    /**
     * Match a given request.
     *
     * @param  Request $request
     *
     * @return RouteMatch|null
     */
    public function match(Request $request)
    {
        // TODO: Implement match() method.
        return new RouteMatch(array());
    }

    /**
     * Assemble the route.
     *
     * @param  array $params
     * @param  array $options
     *
     * @return mixed
     */
    public function assemble(array $params = array(), array $options = array())
    {
        // TODO: Implement assemble() method.
    }

    /**
     * Get a list of parameters used while assembling.
     *
     * @return array
     */
    public function getAssembledParams()
    {
        // TODO: Implement getAssembledParams() method.
    }

}