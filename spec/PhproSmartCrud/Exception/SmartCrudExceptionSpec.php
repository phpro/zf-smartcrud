<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/veewee/PhproSmartCrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace spec\PhproSmartCrud\Exception;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class SmartCrudExceptionSpec
 *
 * @package spec\PhproSmartCrud\Exception
 */
class SmartCrudExceptionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('PhproSmartCrud\Exception\SmartCrudException');
    }

    public function it_should_extend_Exception()
    {
        $this->shouldBeAnInstanceOf('Exception');
    }
}
