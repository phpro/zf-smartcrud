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
use PhproSmartCrud\Event\CrudEvent;
use Prophecy\Argument;
use Prophecy\Prophet;

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

    function it_should_extend_PhproSmartCrud_AbstractCrudService()
    {
        $this->shouldBeAnInstanceOf('PhproSmartCrud\Service\AbstractCrudService');
    }

    /**
     * @todo find a way to mock the callback function
     *
     * @param \Zend\EventManager\EventManager $eventManager
     * @param \PhproSmartCrud\Event\CrudEvent $event
     */
    function it_should_trigger_before_create_event($eventManager, $event)
    {
        $prophet = new Prophet();
        $prophecy = $prophet->prophesize();
        $prophecy->beforeUpdate()->willReturn(true);

        $callback = $prophecy->reveal();
        $eventManager->attach(CrudEvent::BEFORE_CREATE, array($callback, 'beforeUpdate'));
        $this->setEventManager($eventManager);
        $this->create();

        $callback->beforeUpdate($event)->shouldHaveBeenCalled();
    }
}
