<?php

namespace PhproSmartCrud\Service;

use PhproSmartCrud\Exception\SmartCrudException;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class CreateServiceFactory
 *
 * @package PhproSmartCrud\Service
 */
class CreateServiceFactory extends AbstractActionServiceFactory
{

    public function getServiceKey()
    {
        return 'PhproSmartCrud\Service\CreateService';
    }
}
