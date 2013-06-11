<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/veewee/PhproSmartCrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace spec\PhproSmartCrud\Service;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class CreateServiceSpec
 *
 * @package spec\PhproSmartCrud\Service
 */
class CreateServiceSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('PhproSmartCrud\Service\CreateService');
    }

    /**
     * @param \Zend\EventManager\EventManager $eventManager
     * @param \Zend\EventManager\Event $event
     */
    function it_should_trigger_before_create_event($eventManager, $event)
    {

    }
}
