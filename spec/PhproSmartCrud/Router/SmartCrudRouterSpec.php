<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/veewee/PhproSmartCrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace spec\PhproSmartCrud\Router;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class SmartCrudRouterSpec
 *
 * @package spec\PhproSmartCrud\Router
 */
class SmartCrudRouterSpec extends ObjectBehavior
{

    const TEST_ROUTE_NAME = 'smartcrudroute';

    public function let()
    {
        $this->beConstructedWith(self::TEST_ROUTE_NAME);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('PhproSmartCrud\Router\SmartCrudRouter');
    }

    public function it_should_extend_Zend_RouteInterface()
    {
        $this->shouldBeAnInstanceOf('Zend\Mvc\Router\Http\Segment');
    }

    public function it_should_have_default_values()
    {
        $this->getDefaultParams()->shouldHaveDefaultActionConfigurationValues('create');
        $this->getDefaultParams()->shouldHaveDefaultActionConfigurationValues('update');
        $this->getDefaultParams()->shouldHaveDefaultActionConfigurationValues('delete');
        $this->getDefaultParams()->shouldHaveDefaultActionConfigurationValues('read');
    }

    public function getMatchers()
    {
        return [
            'haveDefaultActionConfigurationValues' => function ($subject, $value) {
                    if (!array_key_exists($value, $subject)) {
                        return false;
                    }
                    $config = $subject[$value];
                    return array_key_exists('listeners', $config)
                           && is_array($config['listeners'])
                           && array_key_exists('entity', $config)
                           && array_key_exists('output-model', $config)
                           && $config['output-model'] == 'PhproSmartCrud\View\Model\ViewModel'
                           && array_key_exists('form', $config)
                        ;
                },
        ];
    }

}
