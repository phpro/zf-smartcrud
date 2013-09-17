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
 * Class ListService
 *
 * @package PhproSmartCrud\Service
 */
class ListService extends AbstractCrudService
{

    /**
     * @return array|\Traversable
     */
    public function getList()
    {
        $em = $this->getEventManager();
        $em->trigger($this->createEvent(CrudEvent::BEFORE_LIST));

        $gateway = $this->getGateway();
        $result = $gateway->getList($this->getEntity(), $this->getParameters()->fromQuery());

        $em->trigger($this->createEvent(CrudEvent::AFTER_LIST));
        return $result;
    }

}
