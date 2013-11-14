<?php

namespace PhproSmartCrud\Service;

use PhproSmartCrud\Exception\SmartCrudException;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class CrudServiceFactory
 *
 * @package PhproSmartCrud\Service
 */
class CrudServiceFactory
    implements FactoryInterface, ServiceLocatorAwareInterface
{

    /**
     * The config key in the service manager
     */
    const CONFIG_KEY = 'PhproSmartcrudConfig';

    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return $this
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

    /**
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return CrudService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->setServiceLocator($serviceLocator);
        /** @var CrudService $smartCrud  */
        $smartCrud = $serviceLocator->get('phpro.smartcrud.crud');

        $this->configureParameters($smartCrud);
        $this->configureGateway($smartCrud);
        $this->configureListeners($smartCrud);

        return $smartCrud;
    }

    /**
     * Load smartcrud config from the serviceManager
     *
     * @param string|null $key
     *
     * @return array|object|string
     * @throws \PhproSmartCrud\Exception\SmartCrudException
     */
    public function getConfig($key = null)
    {
        $serviceLocator = $this->getServiceLocator();
        $globalConfig = $serviceLocator->get('Config');
        if (!isset($globalConfig[self::CONFIG_KEY])) {
            throw new SmartCrudException('No smartcrud config provided');
        }

        $config = $globalConfig[self::CONFIG_KEY];
        if ($key) {
            return array_key_exists($key, $config) ? $config[$key] : null;
        }

        return $config;
    }

    /**
     * @param CrudService $smartCrud
     *
     * @return $this
     */
    public function configureParameters($smartCrud)
    {
        $serviceLocator = $this->getServiceLocator();
        $parameterService = $serviceLocator->get('PhproSmartCrud\Service\ParametersService');
        $smartCrud->setParameters($parameterService);

        return $this;
    }

    /**
     * @param CrudService $smartCrud
     *
     * @return $this
     * @throws \PhproSmartCrud\Exception\SmartCrudException
     */
    public function configureGateway($smartCrud)
    {
        $config = $this->getConfig('gateway');
        $serviceLocator = $this->getServiceLocator();

        if (!$serviceLocator->has($config)) {
            throw new SmartCrudException(sprintf('The smartcrud gateway class %s could not be found', $config));
        }

        $gateway = $serviceLocator->get($config);
        $smartCrud->setGateway($gateway);
        return $this;
    }

    /**
     * @param CrudService $smartCrud
     *
     * @return $this
     * @throws \PhproSmartCrud\Exception\SmartCrudException
     */
    public function configureListeners($smartCrud)
    {
        $config = $this->getConfig('listeners');
        $serviceLocator = $this->getServiceLocator();

        if (!is_array($config)) {
            return $this;
        }

        foreach ($config as $listener) {
            if (!$serviceLocator->has($listener)) {
                throw new SmartCrudException(sprintf('The smartcrud listener class %s could not be found', $listener));
            }

            $eventListener = $serviceLocator->get($listener);
            $smartCrud->getEventManager()->attach($eventListener);
        }

        return $this;
    }

}
