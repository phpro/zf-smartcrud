<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/veewee/Phpro\SmartCrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace spec\Phpro\SmartCrud\View\Model;

use PhpSpec\ObjectBehavior;

/**
 * Class ViewModelSpec
 *
 * @package spec\Phpro\SmartCrud\Output
 */
class ViewModelBuilderSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Phpro\SmartCrud\View\Model\ViewModelBuilder');
    }

    /**
     * @param \Zend\Http\PhpEnvironment\Request           $request
     * @param \Phpro\SmartCrud\Service\SmartServiceResult $SmartServiceResult
     * @param \StdClass                                   $entity
     */
    public function it_should_build_a_view_model($request, $SmartServiceResult)
    {
        $this->build($request, $SmartServiceResult, 'create')->shouldBeAnInstanceOf('\Zend\View\Model\ViewModel');
    }

    public function it_should_have_a_default_template()
    {
        $this->getTemplate()->shouldReturn('phpro-smartcrud/%s');
        $this->setTemplate('aName')->shouldReturn($this);
        $this->getTemplate()->shouldReturn('aName');
    }

    /**
     * @param \Zend\Http\PhpEnvironment\Request           $request
     * @param \Phpro\SmartCrud\Service\SmartServiceResult $SmartServiceResult
     * @param \StdClass                                   $entity
     */
    public function it_should_build_a_json_model_when_request_is_xml_http_request($request, $SmartServiceResult)
    {
        $request->isXmlHttpRequest()->willReturn(true);
        $this->build($request, $SmartServiceResult, 'create')->shouldBeAnInstanceOf('\Zend\View\Model\JsonModel');
    }
}
