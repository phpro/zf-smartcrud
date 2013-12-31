<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/veewee/PhproSmartCrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace PhproSmartCrud\Console\Helper;

use Symfony\Component\Console\Helper\Helper;
use PhproSmartCrud\Service\AbstractSmartCrudServiceFactory;
use PhproSmartCrud\Gateway\AbstractGatewayFactory;

/**
 * Class GatewayListHelper
 *
 * @package PhproSmartCrud\Console\Helper
 */
class GatewayListHelper extends Helper
{

    /**
     * @return string
     */
    public function getDefault()
    {
        $config = $this->getHelperSet()->get('Config')->getConfig();
        $configKey = AbstractSmartCrudServiceFactory::CONFIG_KEY;
        $section = AbstractSmartCrudServiceFactory::CONFIG_DEFAULT;
        $gateway = AbstractSmartCrudServiceFactory::CONFIG_GATEWAY_KEY;
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
