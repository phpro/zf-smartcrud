<?php
return array(
    'service_manager' => array(
        'abstract_factories' => array(
            'PhproSmartCrud\Factory\AbstractCrudFactory',
        ),
        'factories' => array(
            'phpro.smartcrud'           => 'PhproSmartCrud\Service\CrudServiceFactory',
            'phpro.smartcrud.params'    => 'PhproSmartCrud\Service\ParametersService',
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
            'phpro.smartcrud.listener.bjyauthorize'     => 'PhproSmartCrud\Listener\BjyAuthorize',
            'phpro.smartcrud.listener.flashmessenger'   => 'PhproSmartCrud\Listener\FlashMessenger',

            // View models
            'phpro.smartcrud.view.model.json'       =>  'PhproSmartCrud\View\Model\JsonModel',
            'phpro.smartcrud.view.model.redirect'   =>  'PhproSmartCrud\View\Model\RedirectModel',
            'phpro.smartcrud.view.model.view'       =>  'PhproSmartCrud\View\Model\ViewModel',

            // View strategies
            'phpro.smartcrud.view.strategy.json'        =>  'PhproSmartCrud\View\Strategy\JsonStrategy',
            'phpro.smartcrud.view.strategy.redirect'    =>  'PhproSmartCrud\View\Strategy\RedirectStrategy',
        ),
        // Make sure to generate new instances ...
        'shared' => array(
            'phpro.smartcrud.gateway.doctrine'      =>  false,
            'phpro.smartcrud.gateway.zenddb'        =>  false,
            'phpro.smartcrud.view.model.json'       =>  false,
            'phpro.smartcrud.view.model.redirect'   =>  false,
            'phpro.smartcrud.view.model.view'       =>  false,
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'phpro.smartcrud.controller' => 'PhproSmartCrud\Controller\CrudController'
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'phpro-smartcrud' => __DIR__ . '/../view',
        ),
        'strategies' => array(
            'phpro.smartcrud.view.strategy.json',
            'phpro.smartcrud.view.strategy.redirect',
       )
    ),
);
