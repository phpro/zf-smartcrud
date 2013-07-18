<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/veewee/PhproSmartCrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace PhproSmartCrud\Event;

use Zend\EventManager\Event;

/**
 * Class CrudEvent
 *
 * @package PhproSmartCrud\Event
 */
class CrudEvent extends Event
{

    const BEFORE_LIST = 'before-list';
    const AFTER_LIST = 'after-list';
    const BEFORE_CREATE = 'before-create';
    const AFTER_CREATE = 'after-create';
    const BEFORE_READ = 'before-read';
    const AFTER_READ = 'after-read';
    const BEFORE_UPDATE = 'before-update';
    const AFTER_UPDATE = 'after-update';
    const BEFORE_DELETE = 'before-delete';
    const AFTER_DELETE = 'after-delete';
    const BEFORE_VALIDATE = 'before-validate';
    const AFTER_VALIDATE = 'after-validate';

    /**
     * Shortcut function to retreive entity
     *
     * @return object|string
     */
    public function getEntity()
    {
        return $this->getTarget();
    }

}
