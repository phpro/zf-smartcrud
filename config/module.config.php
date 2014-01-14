<?php
return array(
    'service_manager' => array(
        "abstract_factories" => array(
            'Phpro\SmartCrud\Gateway\AbstractGatewayFactory',
            'Phpro\SmartCrud\Service\AbstractSmartServiceFactory',
        ),
        'factories' => array(
            'Phpro\SmartCrud\Service\ParametersService'    => 'Phpro\SmartCrud\Service\ParametersService',
            'Phpro\SmartCrud\Console\Application'          => 'Phpro\SmartCrud\Console\ApplicationFactory',
        ),
        'invokables' => array(
            // Services
            'Phpro\SmartCrud\Service\CrudService'     => 'Phpro\SmartCrud\Service\CrudService',
            'Phpro\SmartCrud\Service\ListService'     => 'Phpro\SmartCrud\Service\ListService',
            'Phpro\SmartCrud\Service\CreateService'   => 'Phpro\SmartCrud\Service\CreateService',
            'Phpro\SmartCrud\Service\ReadService'     => 'Phpro\SmartCrud\Service\ReadService',
            'Phpro\SmartCrud\Service\UpdateService'   => 'Phpro\SmartCrud\Service\UpdateService',
            'Phpro\SmartCrud\Service\DeleteService'   => 'Phpro\SmartCrud\Service\DeleteService',

            // Gateways
            'Phpro\SmartCrud\Gateway\DoctrineCrudGateway'  => 'Phpro\SmartCrud\Gateway\DoctrineCrudGateway',
            'Phpro\SmartCrud\Gateway\ZendDbCrudGateway'    => 'Phpro\SmartCrud\Gateway\ZendDbCrudGateway',

            // Listeners
            'Phpro\SmartCrud\Listener\BjyAuthorize'     => 'Phpro\SmartCrud\Listener\BjyAuthorize',
            'Phpro\SmartCrud\Listener\FlashMessenger'   => 'Phpro\SmartCrud\Listener\FlashMessenger',

        ),
        'aliases' => array(
            'zf-smartcrud.cli' => 'Phpro\SmartCrud\Console\Application'
        ),
        // Make sure to generate new instances ...
        'shared' => array(
            'Phpro\SmartCrud\View\Model\JsonModel'       =>  false,
            'Phpro\SmartCrud\View\Model\RedirectModel'   =>  false,
            'Phpro\SmartCrud\View\Model\ViewModel'       =>  false,
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Phpro\SmartCrud\Controller\CrudController' => 'Phpro\SmartCrud\Controller\CrudController'
        ),
        'abstract_factories' => array(
            'Phpro\SmartCrud\\Controller\\AbstractCrudControllerFactory',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'phpro-smartcrud' => __DIR__ . '/../view',
        )
    ),
);
