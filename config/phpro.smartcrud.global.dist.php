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
        'gateway' => array(
            'type' => 'phpro.smartcrud.gateway.doctrine',
            'options' => array(
                'object_manager' => 'Doctrine\ORM\EntityManager',
            ),
        ),

        /**
         * Add default listeners to the smartcrud
         */
        'listeners' => array(
            'phpro.smartcrud.listener.flashmessenger',
            'phpro.smartcrud.listener.bjyauthorize',
        ),
    ),

);
