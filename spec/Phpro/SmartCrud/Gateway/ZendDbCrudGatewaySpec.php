<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/phpro/zf-smartcrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace spec\Phpro\SmartCrud\Gateway;

use PhpSpec\ObjectBehavior;

/**
 * Class ZendDbCrudGatewaySpec
 *
 * @package spec\Phpro\SmartCrud\Gateway
 */
class ZendDbCrudGatewaySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Phpro\SmartCrud\Gateway\ZendDbCrudGateway');
    }

    public function it_should_implement_Phpro_SmartCrud_CrudGatewayInterface()
    {
        $this->shouldBeAnInstanceOf('Phpro\SmartCrud\Gateway\CrudGatewayInterface');
    }
}
