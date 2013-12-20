<?php
namespace PhproSmartCrud;

return array(
    /**
     * Smartcrud configuration
     */
    'PhproSmartcrudConfig' => array(
        /**
         * Default gateway object
         */
        'gateway' => 'custom.doctrine.gateway',

        /**
         * Add default listeners to the smartcrud
         */
        'listeners' => array(
            'phpro.smartcrud.listener.flashmessenger',
            'phpro.smartcrud.listener.bjyauthorize',
        ),
    ),

    /**
     * Configure custom gateways
     */
    'phpro-smartcrud-gateway' => array(
        'custom.doctrine.gateway' => array(
            'type' => 'phpro.smartcrud.gateway.doctrine',
            'options' => array(
                'object_manager' => 'Doctrine\ORM\EntityManager',
            ),
        )
    ),

);
