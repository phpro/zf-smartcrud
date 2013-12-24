<?php

namespace PhproSmartCrud\Service;

use PhproSmartCrud\Exception\SmartCrudException;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ListServiceFactory
 *
 * @package PhproSmartCrud\Service
 */
class ListServiceFactory extends AbstractActionServiceFactory
{

    public function getServiceKey()
    {
        return 'PhproSmartCrud\Service\ListService';
    }
}
