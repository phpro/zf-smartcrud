<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/veewee/PhproSmartCrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace spec\PhproSmartCrud\Gateway;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class DoctrineCrudGatewaySpec
 *
 * @package spec\PhproSmartCrud\Gateway
 */
class DoctrineCrudGatewaySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('PhproSmartCrud\Gateway\DoctrineCrudGateway');
    }

    function it_should_implement_PhproSmartCrud_GatewayInterface()
    {
        $this->shouldImplement('PhproSmartCrud\Gateway\CrudGatewayInterface');
    }

    function it_should_extend_PhproSmartCrud_AbstractGateway()
    {
        $this->shouldBeAnInstanceOf('PhproSmartCrud\Gateway\AbstractCrudGateway');
    }
}
