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

            // Listeners
            'PhproSmartCrud\Listener\BjyAuthorize'     => 'PhproSmartCrud\Listener\BjyAuthorize',
            'PhproSmartCrud\Listener\FlashMessenger'   => 'PhproSmartCrud\Listener\FlashMessenger',
        ),
        'invokables' => array(

            // Services
            'Phpro\SmartCrud\Service\CrudService'     => 'Phpro\SmartCrud\Service\CrudService',
            'Phpro\SmartCrud\Service\ListService'     => 'Phpro\SmartCrud\Service\ListService',
            'Phpro\SmartCrud\Service\CreateService'   => 'Phpro\SmartCrud\Service\CreateService',
            'Phpro\SmartCrud\Service\ReadService'     => 'Phpro\SmartCrud\Service\ReadService',
            'Phpro\SmartCrud\Service\UpdateService'   => 'Phpro\SmartCrud\Service\UpdateService',
            'Phpro\SmartCrud\Service\DeleteService'   => 'Phpro\SmartCrud\Service\DeleteService',
            'Phpro\SmartCrud\Service\PaginatorServiceFactory' => 'Phpro\SmartCrud\Service\PaginatorServiceFactory',

            // Gateways
            'Phpro\SmartCrud\Gateway\DoctrineCrudGateway'  => 'Phpro\SmartCrud\Gateway\DoctrineCrudGateway',
            'Phpro\SmartCrud\Gateway\ZendDbCrudGateway'    => 'Phpro\SmartCrud\Gateway\ZendDbCrudGateway',

            // View Model Builder
            'Phpro\SmartCrud\View\Model\ViewModelBuilder' => 'Phpro\SmartCrud\View\Model\ViewModelBuilder',
        ),
        'aliases' => array(
            'zf-smartcrud.cli' => 'Phpro\SmartCrud\Console\Application'
        ),
        'shared' => array(
            'Phpro\SmartCrud\View\Model\ViewModelBuilder' => false,
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'Phpro\SmartCrud\Controller\CrudController' => 'Phpro\SmartCrud\Controller\CrudController'
        ),
        'abstract_factories' => array(
            'Phpro\SmartCrud\Controller\AbstractCrudControllerFactory',
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
            'phpro-smartcrud/partial/module-delete' => __DIR__ . '/../view/partials/modal-delete',
        ),
        'template_path_stack' => array(
            'phpro-smartcrud' => __DIR__ . '/../view',
        )
    ),
    'asset_manager' => array(
        'resolver_configs' => array(
            'paths' => array(
                __DIR__ . '/../assets',
            ),
        ),
    ),
);
