<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/phpro/zf-smartcrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace spec\PhproSmartCrud\Event;

use PhpSpec\ObjectBehavior;

/**
 * Class CrudEventSpec
 *
 * @package spec\PhproSmartCrud\Event
 */
class CrudEventSpec extends ObjectBehavior
{

    /**
     * @param \stdClass $entity
     */
    public function let($entity)
    {
        $this->setTarget($entity);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('PhproSmartCrud\Event\CrudEvent');
    }

    public function it_should_extend_Zend_Event()
    {
        $this->shouldBeAnInstanceOf('Zend\EventManager\Event');
    }

    public function it_should_have_an_entity()
    {
        $this->getEntity()->shouldReturn($this->getTarget());
    }
}
