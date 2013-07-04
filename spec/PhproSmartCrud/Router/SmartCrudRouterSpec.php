<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/veewee/PhproSmartCrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace spec\PhproSmartCrud\Router;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class SmartCrudRouterSpec
 *
 * @package spec\PhproSmartCrud\Router
 */
class SmartCrudRouterSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('PhproSmartCrud\Router\SmartCrudRouter');
    }

    public function it_should_extend_Zend_RouteInterface()
    {
        $this->shouldBeAnInstanceOf('Zend\Mvc\Router\Http\RouteInterface');
    }

}
