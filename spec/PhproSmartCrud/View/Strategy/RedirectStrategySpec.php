<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/veewee/PhproSmartCrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace spec\PhproSmartCrud\View\Strategy;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class RedirectStrategySpec
 *
 * @package spec\PhproSmartCrud\View\Strategy
 */
class RedirectStrategySpec extends AbstractStrategySpec
{

    public function it_should_extend_AbstractStrategy()
    {
        $this->shouldBeAnInstanceOf('PhproSmartCrud\View\Strategy\AbstractStrategy');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('PhproSmartCrud\View\Strategy\RedirectStrategy');
    }

    /**
     * @param \Zend\Mvc\MvcEvent $mvcEvent
     * @param \PhproSmartCrud\View\Model\RedirectModel $model
     * @param \Zend\Http\PhpEnvironment\Request $request
     * @param \Zend\Http\PhpEnvironment\Response $response
     */
    public function it_should_render_on_redirect_model($mvcEvent, $model, $request, $response)
    {
        $this->mockMvcEvent($mvcEvent, $model, $request, $response);
        $this->render($mvcEvent)->shouldReturn($response);
    }

}
