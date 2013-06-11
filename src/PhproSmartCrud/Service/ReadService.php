<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/veewee/PhproSmartCrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace PhproSmartCrud\Service;

use PhproSmartCrud\Event\CrudEvent;

/**
 * Class ReadService
 *
 * @package PhproSmartCrud\Service
 */
class ReadService extends AbstractCrudService
{

    /**
     * @return mixed
     */
    public function read()
    {
        $em = $this->getEventManager();
        $em->trigger($this->createEvent(CrudEvent::BEFORE_READ));

        $gateway = $this->getGateway();
        $result = $gateway->read($this->getEntity(), $this->getParameters());

        $em->trigger($this->createEvent(CrudEvent::AFTER_READ));
        return $result;
    }

}