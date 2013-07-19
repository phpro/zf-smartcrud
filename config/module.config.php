<?php
return array(
    'service_manager' => array(
        'factories' => array(
            'phpro.smartcrud'           => 'PhproSmartCrud\Service\CrudServiceFactory',
        ),
        'invokables' => array(
            // Services
            'phpro.smartcrud.crud'      => 'PhproSmartCrud\Service\CrudService',
            'phpro.smartcrud.list'      => 'PhproSmartCrud\Service\ListService',
            'phpro.smartcrud.create'    => 'PhproSmartCrud\Service\CreateService',
            'phpro.smartcrud.read'      => 'PhproSmartCrud\Service\ReadService',
            'phpro.smartcrud.update'    => 'PhproSmartCrud\Service\UpdateService',
            'phpro.smartcrud.delete'    => 'PhproSmartCrud\Service\DeleteService',

            // Gateways
            'phpro.smartcrud.gateway.doctrine'  => 'PhproSmartCrud\Gateway\DoctrineCrudGateway',
            'phpro.smartcrud.gateway.zenddb'    => 'PhproSmartCrud\Gateway\ZendDbCrudGateway',

            // Listeners
            'phpro.smartcrud.listener.bjyauthorize'        => 'PhproSmartCrud\Listener\BjyAuthorize',
            'phpro.smartcrud.listener.flashmessenger'   => 'PhproSmartCrud\Listener\FlashMessenger',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'phpro-smartcrud' => __DIR__ . '/../view',
        ),
    ),
);
