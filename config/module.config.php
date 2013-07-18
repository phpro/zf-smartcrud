<?php
return array(
    'service_manager' => array(
        'invokables' => array(
            'phpro.smartcrud.crud'      => 'PhproSmartCrud\Service\CrudService',
            'phpro.smartcrud.list'      => 'PhproSmartCrud\Service\ListService',
            'phpro.smartcrud.create'    => 'PhproSmartCrud\Service\CreateService',
            'phpro.smartcrud.read'      => 'PhproSmartCrud\Service\ReadService',
            'phpro.smartcrud.update'    => 'PhproSmartCrud\Service\UpdateService',
            'phpro.smartcrud.delete'    => 'PhproSmartCrud\Service\DeleteService',
            'phpro.smartcrud.gateway.doctrine'  => 'PhproSmartCrud\Gateway\DoctrineCrudGateway',
            'phpro.smartcrud.gateway.zenddb'    => 'PhproSmartCrud\Gateway\ZendDbCrudGateway',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'phpro-smartcrud' => __DIR__ . '/../view',
        ),
    ),
);
