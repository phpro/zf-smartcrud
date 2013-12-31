<?php
namespace PhproSmartCrud;
use \PhproSmartCrud\Service\AbstractSmartCrudServiceFactory;


return array(

    /**
     * Configure custom gateways
     */
    'phpro-smartcrud-gateway' => array(
        'custom.doctrine.gateway' => array(
            'type' => 'PhproSmartCrud\Gateway\DoctrineCrudGateway',
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
            AbstractSmartCrudServiceFactory::CONFIG_GATEWAY_KEY => 'PhproSmartCrud\Gateway\DoctrineCrudGateway',
        ),
        'Admin\Service\UserServiceFactory' => array(
            'default' => array(
                AbstractSmartCrudServiceFactory::CONFIG_ENTITY_CLASS => 'App\Entity\Country',
                AbstractSmartCrudServiceFactory::CONFIG_OUTPUT_MODEL => 'PhproSmartCrud\View\Model\ViewModel',
                AbstractSmartCrudServiceFactory::CONFIG_FORM_KEY     => 'App\Form\Country'
            ),
            'create' => array(
                AbstractSmartCrudServiceFactory::CONFIG_SERVICE_KEY => '\PhproSmartCrud\Service\CreateService',
                AbstractSmartCrudServiceFactory::CONFIG_LISTENERS_KEY => array(

                ),
            ),
            'update' => array(
                AbstractSmartCrudServiceFactory::CONFIG_SERVICE_KEY => '\PhproSmartCrud\Service\UpdateService',
                AbstractSmartCrudServiceFactory::CONFIG_LISTENERS_KEY => array(

                ),
            )

        )
    ),
);
*/