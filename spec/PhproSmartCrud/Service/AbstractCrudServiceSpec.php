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
 * Class AbstractCrudActionServiceSpec
 *
 * @package spec\PhproSmartCrud\Service
 * @todo spec abstract classes
 */
class AbstractCrudServiceSpec extends ObjectBehavior
{

    /**
     * @parameter \PhproSmartCrud\Service\AbstractCrudService $service
     * @todo find a way to spec abstract classes
     */
    public function it_should_have_parameters($service)
    {
        /*$parameters = array(1,2,3);
        $service->setParameters($parameters)->shouldReturn($service);
        $service->getParameters()->shouldReturn($parameters);*/
    }

}
