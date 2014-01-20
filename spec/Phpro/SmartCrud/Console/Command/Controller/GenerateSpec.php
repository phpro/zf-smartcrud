<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/phpro/zf-smartcrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace spec\Phpro\SmartCrud\Console\Command\Controller;

use PhpSpec\ObjectBehavior;

/**
 * Class GenerateSpec
 *
 * @package spec\Phpro\SmartCrud\Console\Command\Controller
 */
class GenerateSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Phpro\SmartCrud\Console\Command\Controller\Generate');
    }

    public function it_should_extend_Symfony_Console_Command()
    {
        $this->shouldBeAnInstanceOf('Symfony\Component\Console\Command\Command');
    }

}
