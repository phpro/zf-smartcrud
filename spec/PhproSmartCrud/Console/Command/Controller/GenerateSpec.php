<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/phpro/zf-smartcrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace spec\PhproSmartCrud\Console\Command\Controller;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class GenerateSpec
 *
 * @package spec\PhproSmartCrud\Console\Command\Controller
 */
class GenerateSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('PhproSmartCrud\Console\Command\Controller\Generate');
    }

    public function it_should_extend_Symfony_Console_Command()
    {
        $this->shouldBeAnInstanceOf('Symfony\Component\Console\Command\Command');
    }

}
