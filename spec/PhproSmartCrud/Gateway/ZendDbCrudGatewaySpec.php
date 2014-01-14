<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/phpro/zf-smartcrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace spec\PhproSmartCrud\Gateway;

use PhpSpec\ObjectBehavior;

/**
 * Class ZendDbCrudGatewaySpec
 *
 * @package spec\PhproSmartCrud\Gateway
 */
class ZendDbCrudGatewaySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('PhproSmartCrud\Gateway\ZendDbCrudGateway');
    }

    public function it_should_implement_PhproSmartCrud_CrudGatewayInterface()
    {
        $this->shouldBeAnInstanceOf('PhproSmartCrud\Gateway\CrudGatewayInterface');
    }

}
