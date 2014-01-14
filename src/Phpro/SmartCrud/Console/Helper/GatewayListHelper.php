<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/phpro/zf-smartcrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Phpro\SmartCrud\Console\Helper;

use Symfony\Component\Console\Helper\Helper;
use Phpro\SmartCrud\Service\AbstractSmartServiceFactory;
use Phpro\SmartCrud\Gateway\AbstractGatewayFactory;

/**
 * Class GatewayListHelper
 *
 * @package Phpro\SmartCrud\Console\Helper
 */
class GatewayListHelper extends Helper
{

    /**
     * @return string
     */
    public function getDefault()
    {
        $config = $this->getHelperSet()->get('Config')->getConfig();
        $configKey = AbstractSmartServiceFactory::CONFIG_KEY;
        $section = AbstractSmartServiceFactory::CONFIG_DEFAULT;
        $gateway = AbstractSmartServiceFactory::CONFIG_GATEWAY_KEY;
        if (!isset($config[$configKey][$section][$gateway])) {
            return '';
        }

        return $config[$configKey][$section][$gateway];
    }

    /**
     * @return array
     */
    public function getList()
    {
        $config = $this->getHelperSet()->get('Config')->getConfig();
        $configKey = AbstractGatewayFactory::FACTORY_NAMESPACE;
        $gateways = isset($config[$configKey]) ? array_keys($config[$configKey]) :  array();

        return $gateways;
    }

    /**
     * Get the canonical name of this helper.
     *
     * @see \Symfony\Component\Console\Helper\HelperInterface::getName()
     * @return string
     */
    public function getName()
    {
        return 'gatewayList';
    }
}
