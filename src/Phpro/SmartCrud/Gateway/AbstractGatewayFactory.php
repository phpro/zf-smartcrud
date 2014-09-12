<?php

namespace Phpro\SmartCrud\Gateway;

use Phpro\SmartCrud\Exception\SmartCrudException;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\ServiceLocatorInterface;

class AbstractGatewayFactory
    implements AbstractFactoryInterface
{
    const FACTORY_NAMESPACE = 'phpro-smartcrud-gateway';

    /**
     * Cache of canCreateServiceWithName lookups
     * @var array
     */
    protected $lookupCache = array();

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param                         $name
     * @param                         $requestedName
     *
     * @return bool
     * @throws ServiceNotFoundException
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        if (array_key_exists($requestedName, $this->lookupCache)) {
            return $this->lookupCache[$requestedName];
        }

        if (!$serviceLocator->has('Config')) {
            return false;
        }

        // Validate object is set
        $config = $serviceLocator->get('Config');
        $namespace = self::FACTORY_NAMESPACE;
        if (!isset($config[$namespace]) || !is_array($config[$namespace]) || !isset($config[$namespace][$requestedName])) {
            $this->lookupCache[$requestedName] = false;

            return false;
        }

        $this->lookupCache[$requestedName] = true;

        return true;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param                         $name
     * @param                         $requestedName
     *
     * @return CrudGatewayInterface
     * @throws \Phpro\SmartCrud\Exception\SmartCrudException
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $config   = $serviceLocator->get('Config');
        $config   = $config[self::FACTORY_NAMESPACE][$requestedName];

        $options = array();
        $type = $config;

        if (is_array($config)) {
            $type = $config['type'];
            if (isset($config['options'])) {
                $options = $config['options'];
            }
        }

        // Create gateway
        if (!$serviceLocator->has($type)) {
            throw new SmartCrudException(sprintf('The smartcrud gateway class %s could not be found', $type));
        }
        $gateway = $serviceLocator->get($type);
        $this->configureGateway($serviceLocator, $gateway, $options);

        return $gateway;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param CrudGatewayInterface    $gateway
     *                                                @param $options
     */
    protected function configureGateway($serviceLocator, $gateway, $options)
    {
        if (!$options || !is_array($options)) {
            return;
        }

        foreach ($options as $key => $value) {
            $method = preg_replace_callback('/_([a-z0-9])/', function ($matches) { return strtoupper($matches[1]); }, $key);
            $setter = 'set' . ucfirst($method);

            if (!method_exists($gateway, $setter)) {
                continue;
            }

            // Try to load value from servicelocator
            if ($serviceLocator->has($value)) {
                $value = $serviceLocator->get($value);
            }

            $gateway->$setter($value);
        }
    }
}
