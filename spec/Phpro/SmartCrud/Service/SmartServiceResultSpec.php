<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/phpro/zf-smartcrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace spec\Phpro\SmartCrud\Service;

use PhpSpec\ObjectBehavior;

/**
 * Class SmartServiceResultSpec
 *
 * @package spec\Phpro\SmartCrud\Service
 */
class SmartServiceResultSpec extends ObjectBehavior
{

    public function it_is_initializable()
    {
        $this->shouldHaveType('Phpro\SmartCrud\Service\SmartServiceResult');
    }

    public function it_should_have_an_entity()
    {
        $entity = new \StdClass();
        $this->setEntity($entity)->shouldReturn($this);
        $this->getEntity()->shouldReturn($entity);
    }

    public function it_should_have_a_form()
    {
        $entity = new \StdClass();
        $this->setForm($entity)->shouldReturn($this);
        $this->getForm()->shouldReturn($entity);
    }

    public function it_should_be_successfull()
    {
        $this->isSuccessFull()->shouldBe(false);
        $this->setSuccess(true)->shouldReturn($this);
        $this->isSuccessFull()->shouldBe(true);
    }

    public function it_should_have_a_list()
    {
        $list = array();
        $this->setList($list)->shouldReturn($this);
        $this->getList()->shouldReturn($list);
    }
}
