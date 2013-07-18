<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/veewee/PhproSmartCrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace PhproSmartCrud\Console;

use \Symfony\Component\Console\Application as CliApplication;

/**
 * Class Application
 *
 * @package PhproSmartCrud\Console\Command
 */
class Application extends CliApplication
{
    /**
     *
     */
    public function __construct()
    {
        parent::__construct('Smartcrud', '0.1');
    }

    /**
     * @return array|\Symfony\Component\Console\Command\Command[]
     */
    protected function getDefaultCommands()
    {
        $defaultCommands = parent::getDefaultCommands();
        $defaultCommands[] = new Command\Controller\Generate();
        return $defaultCommands;
    }


}
