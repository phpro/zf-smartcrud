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
     * Notice: when the page is requested with ajax, a json model will be used!
     *
     * @return array
     */
    public function getDefaultParams()
    {
        return array(
            'controller' => 'PhproSmartCrud\Controller\CrudController',
            'action' => 'list',
            'entity' => null,
            'form' => null,
            'create' => array(
                'service'   => 'PhproSmartCrud\Service\CreateServiceFactory' ,
                'entity'    => null,
                'form'      => null,
                'output-model' => 'PhproSmartCrud\View\Model\ViewModel',
                'listeners' => array() ,
            ),
            'update' => array(
              'service'     => 'PhproSmartCrud\Service\UpdateServiceFactory' ,
              'entity'    => null,
              'form'      => null,
              'output-model' => 'PhproSmartCrud\View\Model\ViewModel',
              'listeners'   => array() ,
            ),
            'delete' => array(
                'service'   => 'PhproSmartCrud\Service\DeleteServiceFactory' ,
                'entity'    => null,
                'form'      => null,
                'output-model' => 'PhproSmartCrud\View\Model\ViewModel',
                'listeners' => array() ,
            ),
            'read' => array(
                'service' => 'PhproSmartCrud\Service\ReadServiceFactory' ,
                'entity'    => null,
                'form'      => null,
                'output-model' => 'PhproSmartCrud\View\Model\ViewModel',
                'listeners' => array() ,
            ),
            'list' => array(
                'service' => 'PhproSmartCrud\Service\ListServiceFactory' ,
                'entity'    => null,
                'form'      => null,
                'output-model' => 'PhproSmartCrud\View\Model\ViewModel',
                'listeners' => array() ,
            ),
            'identifier-name' => 'id',
        );
    }

    /**
     * @return array
     */
    public function getDefaultConstraints()
    {
        return array(
            'action' => 'list|create|read|update|delete',
            'id' => '[0-9]*',
        );
    }

}
