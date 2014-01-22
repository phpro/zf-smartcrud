<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/phpro/zf-smartcrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Phpro\SmartCrud\Console\Command\Controller;

use Phpro\SmartCrud\Controller\AbstractCrudControllerFactory;
use Phpro\SmartCrud\Service\AbstractSmartServiceFactory;
use Symfony\Component\Console\Command\Command as CliCommand;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Config\Writer\PhpArray as PhpArrayWriter;
use Zend\Stdlib\ArrayUtils;

/**
 * Class Generate
 *
 * @package Phpro\SmartCrud\Console\Command\Command
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
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $gateway = $this->getGatewayClass($output);
        $module = $this->getModuleName($output);
        $routePrefix = $this->getRoutePrefix($output);
        $controller = $this->getControllerClass($output);
        $entity = $this->getEntityClass($output);
        $form = $this->getFormClass($output);

        $config = $this->parseConfig($gateway, $routePrefix, $controller, $entity, $form);
        $file = $this->writeConfig($module, $config);

        $output->writeln(sprintf('<fg=green>Configuration added to %s</fg=green>', $file));
        $output->writeln('Smartcrud controller generated.');
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
        $moduleList = $this->getHelper('moduleList')->getList();
        $module = $dialog->askAndValidate($output, 'Please enter the name of the module: ', function ($module) use ($moduleList) {
                if (!in_array($module, $moduleList)) {
                    throw new \RunTimeException('Invalid module: ' . $module);
                }

                $location = sprintf('%s/module/%s/Module.php', getcwd(), $module);
                if (!file_exists($location)) {
                    throw new \RunTimeException(sprintf(
                        'The selected module "%s" is not writable. Make sure that it is in the module directory.',
                        $module
                    ));
                }

                return $module;
            }, false, '', $moduleList);

        return $module;
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
        $entity = $dialog->ask($output, 'Please enter the class of the entity: ', '');
        $entity = str_replace('/', '\\', $entity);

        return $entity;
    }

    /**
     * @param OutputInterface $output
     *
     * @return string
     * @throws \RunTimeException
     */
    protected function getFormClass(OutputInterface $output)
    {
        $dialog = $this->getDialog();
        $form = $dialog->ask($output, 'Please enter the identifier key of the form: ', '');
        $form = str_replace('/', '\\', $form);

        return $form;
    }

    /**
     * @param OutputInterface $output
     *
     * @return string
     * @throws \RunTimeException
     */
    protected function getControllerClass(OutputInterface $output)
    {
        $dialog = $this->getDialog();
        $controller = $dialog->ask($output, 'Please enter the identifier key of the controller: ', '');
        $controller = str_replace('/', '\\', $controller);

        return $controller;
    }

    /**
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
                if (false) {
                    throw new \RunTimeException('Invalid route: ' . $route);
                }

                return $route;
            }, false, '');

        return $route;
    }

    /**
     * @param  OutputInterface   $output
     * @return string
     * @throws \RunTimeException
     */
    protected function getGatewayClass(OutputInterface $output)
    {
        $dialog = $this->getDialog();
        $gatewayHelper = $this->getHelper('gatewayList');
        $gatewayList = $gatewayHelper->getList();
        $defaultGateway = $gatewayHelper->getDefault();

        $question = sprintf('Please enter the service key of the gateway <fg=yellow>(Default: %s)</fg=yellow>:', $defaultGateway);
        $gateway = $dialog->askAndValidate($output, $question, function ($gateway) use ($gatewayList) {
                if (!in_array($gateway, $gatewayList)) {
                    throw new \RunTimeException('Invalid gateway: ' . $gateway);
                }

                return $gateway;
            }, false, $defaultGateway, $gatewayList);

        return $gateway;
    }

    /**
     * @param $gateway
     * @param $routePrefix
     * @param $controller
     * @param $entity
     * @param $form
     *
     * @return array
     */
    protected function parseConfig($gateway, $routePrefix, $controller, $entity, $form)
    {

        $entitySuffix = ltrim($entity, '\\');
        $serviceKey = 'SmartCrudService\\' . $entitySuffix;
        $controllerKey = 'SmartCrudController\\' . $entitySuffix;
        $routeName = str_replace('\\', '-', strtolower($serviceKey));
        $routePrefix = ltrim($routePrefix, '/');

        return array(
            AbstractSmartServiceFactory::CONFIG_KEY => array(
                $serviceKey => array(
                    'default' => array(
                        AbstractSmartServiceFactory::CONFIG_GATEWAY_KEY  => $gateway,
                        AbstractSmartServiceFactory::CONFIG_ENTITY_CLASS => $entity,
                        AbstractSmartServiceFactory::CONFIG_FORM_KEY     => $form,
                    ),
                    'list' => array(
                        AbstractSmartServiceFactory::CONFIG_LISTENERS_KEY => array(),
                    ),
                    'create' => array(
                        AbstractSmartServiceFactory::CONFIG_LISTENERS_KEY => array(),
                    ),
                    'read' => array(
                        AbstractSmartServiceFactory::CONFIG_LISTENERS_KEY => array(),
                    ),
                    'update' => array(
                        AbstractSmartServiceFactory::CONFIG_LISTENERS_KEY => array(),
                    ),
                    'delete' => array(
                        AbstractSmartServiceFactory::CONFIG_LISTENERS_KEY => array(),
                    ),
                ),
            ),

            AbstractCrudControllerFactory::FACTORY_NAMESPACE => array(
                $controllerKey => array(
                    AbstractCrudControllerFactory::CONFIG_VIEW_MODEL_BUILDER => 'Phpro\SmartCrud\View\Model\ViewModelBuilder',
                    AbstractCrudControllerFactory::CONFIG_CONTROLLER => 'Phpro\SmartCrud\\Controller\\CrudController',
                    AbstractCrudControllerFactory::CONFIG_IDENTIFIER => 'id',
                    AbstractCrudControllerFactory::CONFIG_SMART_SERVICE => $serviceKey,
                ),
            ),

            'router' => array(
                'routes' => array(
                    $routeName => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route'    => '/' . $routePrefix . '[/:action[/:id]]',
                            'constraints' => array(
                                'action' => 'list|create|read|update|delete',
                                'id' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => $controller,
                                'action' => 'list',
                            )
                        ),
                    ),
                ),
            ),
        );
    }

    /**
     * @param $module
     * @param $config
     *
     * @return string
     */
    protected function writeConfig($module, $config)
    {
        $file = sprintf('%s/module/%s/config/module.config.php', getcwd(), $module);
        $writer = new PhpArrayWriter();

        // Load local config:
        $localConfig = array();
        if (file_exists($file)) {
            $localConfig = include $file;
            if (!is_array($localConfig)) {
                $localConfig = array();
            }
        }

        // Create backup:
        if ($localConfig) {
            copy($file, $file . '.backup');
        }

        // Merge with local config
        $localConfig = ArrayUtils::merge($localConfig, $config);

        // Write to configuration file
        $writer->toFile($file, $localConfig);

        return $file;
    }

}
