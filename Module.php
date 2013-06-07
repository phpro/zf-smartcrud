<?php

namespace PhproSmartCrud;

use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;

/**
 * Class Module
 *
 * @package PhproSmartCrud
 */
class Module implements ConfigProviderInterface, AutoloaderProviderInterface
{

    /**
     * @inheritdoc
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * @inheritdoc
     */
    public function getConfig($env = null)
    {
        return include __DIR__ . '/config/module.config.php';
    }

}
