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
use Zend\ServiceManager\ServiceManager;

/**
 * Class ServiceManagerHelper
 *
 * @package PhproSmartCrud\Console\Helper
 */
class ServiceManagerHelper extends Helper
{
    protected $serviceManager;

    /**
     * @param ServiceManager $serviceManager
     */
    public function __construct(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    /**
     * Get the ServiceManager instance.
     *
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * Get the canonical name of this helper.
     *
     * @see \Symfony\Component\Console\Helper\HelperInterface::getName()
     * @return string
     */
    public function getName()
    {
        return 'serviceManager';
    }
}
