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
        'gateway' => 'phpro.smartcrud.gateway.doctrine',

        /**
         * Add default listeners to the smartcrud
         */
        'listeners' => array(
            'phpro.smartcrud.listener.flashmessenger'
        ),
    ),

);
