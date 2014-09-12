<?php

namespace spec\Phpro\SmartCrud;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ModuleSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Phpro\SmartCrud\Module');
    }

    public function it_implements_autoloader_provider()
    {
        $this->shouldHaveType('Zend\ModuleManager\Feature\AutoloaderProviderInterface');
    }

    public function it_implements_bootstrap_provider()
    {
        $this->shouldHaveType('Zend\ModuleManager\Feature\BootstrapListenerInterface');
    }

    public function it_implements_config_provider()
    {
        $this->shouldHaveType('Zend\ModuleManager\Feature\ConfigProviderInterface');
    }

    public function it_should_load_autoloader_configuration()
    {
        $this->getAutoloaderConfig()->shouldBeArray();
    }

    public function it_should_load_module_configuration()
    {
        $this->getConfig()->shouldBeArray();
    }

    /**
     * @param \Zend\EventManager\EventInterface $event
     * @param \Zend\Mvc\Application             $app
     * @param \Zend\EventManager\EventManager   $eventManager
     */
    public function it_should_attach_cli_listener_on_bootstrap($event, $app, $eventManager)
    {
        $event->getTarget()->willReturn($app);
        $app->getEventManager()->willReturn($eventManager);
        $eventManager->getSharedManager()->willReturn($eventManager);
        $eventManager->attach(Argument::cetera())->willReturn(null);

        $this->onBootstrap($event);
        $eventManager->attach('zf-smartcrud', 'loadCli.post', Argument::type('array'))->shouldBeCalled();
    }

    /**
     * @param \Zend\EventManager\EventInterface           $event
     * @param \Symfony\Component\Console\Application      $cli
     * @param \Zend\ServiceManager\ServiceManager         $serviceManager
     * @param \Symfony\Component\Console\Helper\HelperSet $helperSet
     */
    public function it_should_configure_cli($event, $cli, $serviceManager, $helperSet)
    {
        $event->getTarget()->willReturn($cli);
        $event->getParam('ServiceManager')->willReturn($serviceManager);
        $cli->addCommands(Argument::type('array'))->willReturn(null);
        $cli->getHelperSet()->willReturn($helperSet);

        $this->loadCli($event);
        $cli->addCommands(Argument::type('array'))->shouldBeCalled();
        $helperSet->set(Argument::cetera())->shouldBeCalled();
    }
}
