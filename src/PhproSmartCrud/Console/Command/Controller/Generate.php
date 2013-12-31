<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/veewee/PhproSmartCrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace PhproSmartCrud\Console\Command\Controller;

use Symfony\Component\Console\Command\Command as CliCommand;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Generate
 *
 * @package PhproSmartCrud\Console\Command\Command
 */
class Generate extends CliCommand
{
    /**
     * Configurate the command
     */
    protected function configure()
    {
        $this
            ->setName('controller:generate')
            ->setDescription('Generate a new smartcrud controller');
    }

    /**
     * @TODO write generation of files
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $module = $this->getModuleName($output);
        $gateway = $this->getGatewayClass($output);
        $entity = $this->getEntityClass($output);
        $route = $this->getRoutePrefix($output);

        $text = print_r(array('TODO: create logica', $module, $entity, $route, $gateway), true);


        $output->writeln($text);
    }

    /**
     * @return DialogHelper
     */
    protected function getDialog()
    {
        return $this->getHelperSet()->get('dialog');
    }

    /**
     * @param OutputInterface $output
     *
     * @return string
     * @throws \RunTimeException
     */
    protected function getModuleName(OutputInterface $output)
    {
        $dialog = $this->getDialog();
        $moduleList = $this->getModuleList();
        $module = $dialog->askAndValidate($output, 'Please enter the name of the module: ', function ($module) use ($moduleList) {
            if (!in_array($module, $moduleList))  {
                throw new \RunTimeException('Invalid module: ' . $module);
            }
            return $module;
        }, false, '', $moduleList);

        return $module;
    }

    /**
     * @TODO retrieve list of registered modules
     * @return array
     */
    protected function getModuleList()
    {
        return array('test', 'test2', 'test3');
    }

    /**
     * @param OutputInterface $output
     *
     * @return string
     * @throws \RunTimeException
     */
    protected function getEntityClass(OutputInterface $output)
    {
        $dialog = $this->getDialog();
        $entityList = $this->getEntityList();
        $entity = $dialog->askAndValidate($output, 'Please enter the class of the entity: ', function ($entity) use ($entityList) {
            if (!in_array($entity, $entityList))  {
                throw new \RunTimeException('Invalid entity: ' . $entity);
            }
            return $entity;
        }, false, '', $entityList);

        return $entity;
    }

    /**
     * @TODO retrieve list of entities
     * @return array
     */
    protected function getEntityList()
    {
        return array('test', 'test2', 'test3');
    }

    /**
     * @TODO validate route
     *
     * @param OutputInterface $output
     *
     * @return string
     * @throws \RunTimeException
     */
    protected function getRoutePrefix(OutputInterface $output)
    {
        $dialog = $this->getDialog();
        $route = $dialog->askAndValidate($output, 'Please enter the prefix of the route: ', function ($route) {
            if (false)  {
                throw new \RunTimeException('Invalid route: ' . $route);
            }
            return $route;
        }, false, '');

        return $route;
    }

    /***
     * @param OutputInterface $output
     * @return string
     * @throws \RunTimeException
     */
    protected function getGatewayClass(OutputInterface $output)
    {
        $dialog = $this->getDialog();
        $gatewayList = $this->getGatewayList();
        $gateway = $dialog->askAndValidate($output, 'Please enter the class of the gateway: ', function ($gateway) use ($gatewayList) {
                if (!in_array($gateway, $gatewayList))  {
                    throw new \RunTimeException('Invalid gateway: ' . $gateway);
                }

                return array_search($gateway, $gatewayList);
            }, false, '', $gatewayList);

        return $gateway;
    }

    /**
     * @return array
     */
    protected function getGatewayList()
    {
        return array(
            'PhproSmartCrud\Gateway\DoctrineCrudGateway' => 'Doctrine',
            'PhproSmartCrud\Gateway\ZendDbCrudGateway'   => 'Zend-Db',
        );
    }

}
