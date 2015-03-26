<?php
namespace Phpro\SmartCrud;
use \Phpro\SmartCrud\Service\AbstractSmartServiceFactory;


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
    AbstractSmartServiceFactory::CONFIG_KEY => array(
        'default' => array(
            AbstractSmartServiceFactory::CONFIG_GATEWAY_KEY => 'Phpro\SmartCrud\Gateway\DoctrineCrudGateway',
        ),
        'default-list => array(
            'options => array(
                'paginator' => array(
                    'adapter_class' => 'Zend\Paginator\Adapter\ArrayAdapter',
                    'page_size' => 50,
                    'query_key' => 'page',
                ),
            ),
        ),
        'Admin\Service\UserServiceFactory' => array(
            'default' => array(
                AbstractSmartServiceFactory::CONFIG_ENTITY_CLASS => 'App\Entity\Country',
                AbstractSmartServiceFactory::CONFIG_OUTPUT_MODEL => 'Phpro\SmartCrud\View\Model\ViewModel',
                AbstractSmartServiceFactory::CONFIG_FORM_KEY     => 'App\Form\Country'
            ),
            'create' => array(
                AbstractSmartServiceFactory::CONFIG_SERVICE_KEY => '\Phpro\SmartCrud\Service\CreateService',
                AbstractSmartServiceFactory::CONFIG_LISTENERS_KEY => array(

                ),
            ),
            'update' => array(
                AbstractSmartServiceFactory::CONFIG_SERVICE_KEY => '\Phpro\SmartCrud\Service\UpdateService',
                AbstractSmartServiceFactory::CONFIG_LISTENERS_KEY => array(

                ),
            )

        )
    ),
);
*/

/*
 * Sample crudontroller configuration
 *
array(
    AbstractCrudControllerFactory::FACTORY_NAMESPACE => array(
        'default' => array(
            AbstractCrudControllerFactory::CONFIG_VIEW_MODEL_BUILDER => 'Phpro\SmartCrud\View\Model\ViewModelBuilder',
        ),
        $controllerKey => array(
            AbstractCrudControllerFactory::CONFIG_CONTROLLER => 'Phpro\SmartCrud\\Controller\\CrudController',
            AbstractCrudControllerFactory::CONFIG_IDENTIFIER => 'id',
            AbstractCrudControllerFactory::CONFIG_SMART_SERVICE => 'Admin\Service\UserServiceFactory',
        ),
    ),
),
*/