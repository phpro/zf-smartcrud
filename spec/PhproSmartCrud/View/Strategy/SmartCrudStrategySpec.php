<?php

namespace spec\PhproSmartCrud\View\Strategy;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Zend\Mvc\MvcEvent;

class SmartCrudStrategySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('PhproSmartCrud\View\Strategy\SmartCrudStrategy');
    }

    public function it_should_extend_Zend_Abstract_Listener_Aggregate()
    {
        $this->shouldBeAnInstanceOf('Zend\EventManager\AbstractListenerAggregate');
    }

    /**
     * @param \Zend\EventManager\EventManagerInterface $events
     */
    public function it_should_add_model_listeners($events)
    {
        $this->attach($events);
        $events->attach(MvcEvent::EVENT_RENDER, Argument::type('array'), Argument::type('int'))->shouldBeCalled();
    }

    public function it_should_not_render_when_a_response_is_available()
    {

    }

    public function it_should_handle_smartcrud_view_model()
    {

    }

    public function it_should_handle_smartcrud_json_model()
    {

    }

    public function it_should_handle_smartcrud_redirect_model()
    {

    }

}
