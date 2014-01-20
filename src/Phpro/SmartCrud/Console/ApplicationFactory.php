<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/phpro/zf-smartcrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Phpro\SmartCrud\Console;

use Phpro\SmartCrud\Version;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\HelperSet;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class Application
 *
 * @package Phpro\SmartCrud\Console\Command
 */
class ApplicationFactory implements FactoryInterface
{

    /**
     * @var \Zend\EventManager\EventManagerInterface
     */
    protected $events;

    /**
     * @var \Symfony\Component\Console\Helper\HelperSet
     */
    protected $helperSet;

    /**
     * @var array
     */
    protected $commands = array();

    /**
     * @param  ServiceLocatorInterface                  $sm
     * @return \Zend\EventManager\EventManagerInterface
     */
    public function getEventManager(ServiceLocatorInterface $sm)
    {
        if (null === $this->events) {
            /* @var $events \Zend\EventManager\EventManagerInterface */
            $events = $sm->get('EventManager');
            $events->addIdentifiers(array(__CLASS__, 'zf-smartcrud'));
            $this->events = $events;
        }

        return $this->events;
    }

    /**
     * {@inheritDoc}
     * @return Application
     */
    public function createService(ServiceLocatorInterface $sl)
    {
        $cli = new Application();
        $cli->setName('SmartCrud Command Line Interface');
        $cli->setVersion(Version::VERSION);

        // Load commands using event
        $this->getEventManager($sl)->trigger('loadCli.post', $cli, array('ServiceManager' => $sl));

        return $cli;
    }

}
