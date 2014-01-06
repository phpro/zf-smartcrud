<?php
return array(
    'service_manager' => array(
        "abstract_factories" => array(
            'PhproSmartCrud\Gateway\AbstractCrudFactory',
            'PhproSmartCrud\Service\AbstractSmartCrudServiceFactory',
        ),
        'factories' => array(
            'PhproSmartCrud\Service\ParametersService'    => 'PhproSmartCrud\Service\ParametersService',

            // Listeners
            'PhproSmartCrud\Listener\BjyAuthorize'     => 'PhproSmartCrud\Listener\BjyAuthorize',
            'PhproSmartCrud\Listener\FlashMessenger'   => 'PhproSmartCrud\Listener\FlashMessenger',

        ),
        'invokables' => array(
            // Services
            'PhproSmartCrud\Service\CrudService'     => 'PhproSmartCrud\Service\CrudService',
            'PhproSmartCrud\Service\ListService'     => 'PhproSmartCrud\Service\ListService',
            'PhproSmartCrud\Service\CreateService'   => 'PhproSmartCrud\Service\CreateService',
            'PhproSmartCrud\Service\ReadService'     => 'PhproSmartCrud\Service\ReadService',
            'PhproSmartCrud\Service\UpdateService'   => 'PhproSmartCrud\Service\UpdateService',
            'PhproSmartCrud\Service\DeleteService'   => 'PhproSmartCrud\Service\DeleteService',

            // Gateways
            'PhproSmartCrud\Gateway\DoctrineCrudGateway'  => 'PhproSmartCrud\Gateway\DoctrineCrudGateway',
            'PhproSmartCrud\Gateway\ZendDbCrudGateway'    => 'PhproSmartCrud\Gateway\ZendDbCrudGateway',

            // View models
            'PhproSmartCrud\View\Model\JsonModel'       =>  'PhproSmartCrud\View\Model\JsonModel',
            'PhproSmartCrud\View\Model\RedirectModel'   =>  'PhproSmartCrud\View\Model\RedirectModel',
            'PhproSmartCrud\View\Model\ViewModel'       =>  'PhproSmartCrud\View\Model\ViewModel',

            // View strategies
            'PhproSmartCrud\View\Strategy\JsonStrategy'        =>  'PhproSmartCrud\View\Strategy\JsonStrategy',
            'PhproSmartCrud\View\Strategy\RedirectStrategy'    =>  'PhproSmartCrud\View\Strategy\RedirectStrategy',
        ),
        // Make sure to generate new instances ...
        'shared' => array(
            'PhproSmartCrud\View\Model\JsonModel'       =>  false,
            'PhproSmartCrud\View\Model\RedirectModel'   =>  false,
            'PhproSmartCrud\View\Model\ViewModel'       =>  false,
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'PhproSmartCrud\Controller\CrudController' => 'PhproSmartCrud\Controller\CrudController'
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'phpro-smartcrud' => __DIR__ . '/../view',
        ),
        'strategies' => array(
            'PhproSmartCrud\View\Strategy\JsonStrategy',
            'PhproSmartCrud\View\Strategy\RedirectStrategy',
       )
    ),
);
