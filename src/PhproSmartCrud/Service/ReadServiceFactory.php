<?php

namespace PhproSmartCrud\Service;

use PhproSmartCrud\Exception\SmartCrudException;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ReadServiceFactory
 *
 * @package PhproSmartCrud\Service
 */
class ReadServiceFactory extends AbstractActionServiceFactory
{

    public function getServiceKey()
    {
        return 'PhproSmartCrud\Service\ReadService';
    }
}
