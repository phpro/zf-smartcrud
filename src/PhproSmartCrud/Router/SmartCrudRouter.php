<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/veewee/PhproSmartCrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace PhproSmartCrud\Router;
use Zend\Mvc\Router\Http\Segment;

/**
 * Class SmartCrudRouter
 *
 * @package PhproSmartCrud\Router
 */
class SmartCrudRouter extends Segment
{

    /**
     * @param string $route
     * @param array  $constraints
     * @param array  $defaults
     */
    public function __construct($route, array $constraints = array(), array $defaults = array())
    {
        $constraints = array_merge($this->getDefaultConstraints(), $constraints);
        $defaults = array_merge($this->getDefaultParams(), $defaults);
        parent::__construct($route, $constraints, $defaults);
    }

    /**
     * @return array
     */
    protected function getDefaultParams()
    {
        return array(
            'controller' => 'phpro.smartcrud',
            'service' => 'phpro.smartcrud.crud',
            'action' => 'list',
            'entity' => null,
            'form' => null,
            'id' => null,
        );
    }

    /**
     * @return array
     */
    protected function getDefaultConstraints()
    {
        return array(
            'action' => 'list|create|read|update|delete',
            'id' => '[0-9]*',
        );
    }

}
