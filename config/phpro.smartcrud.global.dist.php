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
        'gateway' => 'PhproSmartCrud\Gateway\DoctrineCrudGateway',

        /**
         * Add default listeners to the smartcrud
         */
        'listeners' => array(
            'PhproSmartCrud\Listener\FlashMessenger',
             'PhproSmartCrud\Listener\BjyAuthorize',
        ),
    ),

);
