<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/phpro/zf-smartcrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace spec\PhproSmartCrud\View\Model;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class JsonModelSpec
 *
 * @package spec\PhproSmartCrud\Output
 */
class JsonModelSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('PhproSmartCrud\View\Model\JsonModel');
    }

    public function it_should_extend_Zend_View_JsonModel()
    {
        $this->shouldBeAnInstanceOf('Zend\View\Model\JsonModel');
    }
}
