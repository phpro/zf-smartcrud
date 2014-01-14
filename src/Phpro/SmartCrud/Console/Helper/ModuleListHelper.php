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

/**
 * Class ModuleListHelper
 *
 * @package Phpro\SmartCrud\Console\Helper
 */
class ModuleListHelper extends Helper
{

    /**
     * @return array
     */
    public function getList()
   {
       $config = $this->getHelperSet()->get('Config')->getApplicationConfig();

       return isset($config['modules']) ? $config['modules'] : array();
   }

    /**
     * Get the canonical name of this helper.
     *
     * @see \Symfony\Component\Console\Helper\HelperInterface::getName()
     * @return string
     */
    public function getName()
    {
        return 'moduleList';
    }
}
