<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/phpro/zf-smartcrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace spec\PhproSmartCrud\View\Strategy;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Zend\Mvc\MvcEvent;

/**
 * Class AbstractStrategySpec
 *
 * @package spec\PhproSmartCrud\View\Strategy
 */
abstract class AbstractStrategySpec extends ObjectBehavior
{

    /**
     * @param \Zend\Mvc\MvcEvent $mvcEvent
     * @param \Zend\View\Model\ModelInterface $model
     * @param \Zend\Http\PhpEnvironment\Request $request
     * @param \Zend\Http\PhpEnvironment\Response $response
     *
     * @return \Zend\Mvc\MvcEvent
     */
    protected function mockMvcEvent($mvcEvent, $model, $request, $response)
    {
        $mvcEvent->getResult()->willReturn(null);
        $mvcEvent->getViewModel()->willReturn($model);
        $mvcEvent->getRequest()->willReturn($request);
        $mvcEvent->getResponse()->willReturn($response);

        $mvcEvent->setViewModel(Argument::any())->willReturn($mvcEvent);

        return $mvcEvent;
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

    /**
     * @param \Zend\Mvc\MvcEvent $mvcEvent
     * @param \Zend\Http\PhpEnvironment\Response $response
     */
    public function it_should_not_render_while_having_result($mvcEvent, $response)
    {
        $mvcEvent->getResult()->willReturn($response);
        $this->render($mvcEvent)->shouldReturn($response);
    }

    /**
     * @param \Zend\Mvc\MvcEvent $mvcEvent
     * @param \Zend\View\Model\ModelInterface $model
     */
    public function it_should_not_render_on_invalid_model($mvcEvent, $model)
    {
        $mvcEvent->getResult()->willReturn(null);
        $mvcEvent->getViewModel()->willReturn($model);
        $this->render($mvcEvent)->shouldReturn(null);
    }

}
