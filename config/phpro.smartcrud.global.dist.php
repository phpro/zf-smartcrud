<?php
namespace Phpro\SmartCrud;
use \Phpro\SmartCrud\Service\AbstractSmartCrudServiceFactory;


return array(

    /**
     * Configure custom gateways
     */
    'phpro-smartcrud-gateway' => array(
        'custom.doctrine.gateway' => array(
            'type' => 'Phpro\SmartCrud\Gateway\DoctrineCrudGateway',
            'options' => array(
                'object_manager' => 'Doctrine\ORM\EntityManager',
            ),
        )
    ),
);


/*
 * Sample crudservice configuration
 *
array(
    AbstractSmartCrudServiceFactory::CONFIG_KEY => array(
        'default' => array(
            AbstractSmartCrudServiceFactory::CONFIG_GATEWAY_KEY => 'Phpro\SmartCrud\Gateway\DoctrineCrudGateway',
        ),
        'Admin\Service\UserServiceFactory' => array(
            'default' => array(
                AbstractSmartCrudServiceFactory::CONFIG_ENTITY_CLASS => 'App\Entity\Country',
                AbstractSmartCrudServiceFactory::CONFIG_OUTPUT_MODEL => 'Phpro\SmartCrud\View\Model\ViewModel',
                AbstractSmartCrudServiceFactory::CONFIG_FORM_KEY     => 'App\Form\Country'
            ),
            'create' => array(
                AbstractSmartCrudServiceFactory::CONFIG_SERVICE_KEY => '\Phpro\SmartCrud\Service\CreateService',
                AbstractSmartCrudServiceFactory::CONFIG_LISTENERS_KEY => array(

                ),
            ),
            'update' => array(
                AbstractSmartCrudServiceFactory::CONFIG_SERVICE_KEY => '\Phpro\SmartCrud\Service\UpdateService',
                AbstractSmartCrudServiceFactory::CONFIG_LISTENERS_KEY => array(

                ),
            )

        )
    ),
);
*/
/*
 * Sample crucontroller configuration
 *
array(
    AbstractCrudControllerFactory::FACTORY_NAMESPACE => array(
        $controllerKey => array(
            AbstractCrudControllerFactory::CONFIG_CONTROLLER => 'Phpro\SmartCrud\\Controller\\CrudController',
            AbstractCrudControllerFactory::CONFIG_IDENTIFIER => 'id',
            AbstractCrudControllerFactory::CONFIG_SMART_SERVICE => 'Admin\Service\UserServiceFactory',
        ),
    ),
),
 */