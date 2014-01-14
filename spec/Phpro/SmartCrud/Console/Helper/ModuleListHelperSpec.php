<?php

namespace spec\Phpro\SmartCrud\Console\Helper;

class ModuleListHelperSpec extends AbstractHelperSpec
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Phpro\SmartCrud\Console\Helper\ModuleListHelper');
    }

    public function it_should_load_a_modules_list()
    {
        $this->mockConfig(array(
            'modules' => array(
                'SampleModule'
            )
        ));

        $list = $this->getList();
        $list->shouldBeArray();
        $list[0]->shouldBe('SampleModule');
    }

}
