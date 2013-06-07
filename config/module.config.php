<?php
return array(
    'service_manager' => array(
        'invokables' => array(
            'PhproSmartCrud\Service\CrudService',
            'PhproSmartCrud\Service\ListService',
            'PhproSmartCrud\Service\CreateService',
            'PhproSmartCrud\Service\ReadService',
            'PhproSmartCrud\Service\UpdateService',
            'PhproSmartCrud\Service\DeleteService',
            'PhproSmartCrud\Gateway\DoctrineCrudGateway',
            'PhproSmartCrud\Gateway\ZendDbCrudGateway',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'phpro-smartcrud' => __DIR__ . '/../view',
        ),
    ),
);