<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/phpro/zf-smartcrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace PhproSmartCrud\Console\Helper;

use Symfony\Component\Console\Helper\Helper;

/**
 * Class ConfigHelper
 *
 * @package PhproSmartCrud\Console\Helper
 */
class ConfigHelper extends Helper
{

    /**
     * @return array
     */
    public function getConfig()
    {
        $serviceManager = $this->getHelperSet()->get('serviceManager')->getServiceManager();
        $config = $serviceManager->get('Config');

        return $config;
    }

    /**
     * @return array
     */
    public function getApplicationConfig()
    {
        $serviceManager = $this->getHelperSet()->get('serviceManager')->getServiceManager();
        $config = $serviceManager->get('ApplicationConfig');

        return $config;
    }

    /**
     * Get the canonical name of this helper.
     *
     * @see \Symfony\Component\Console\Helper\HelperInterface::getName()
     * @return string
     */
    public function getName()
    {
        return 'Config';
    }
}
