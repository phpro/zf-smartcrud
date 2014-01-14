<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/phpro/zf-smartcrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Phpro\SmartCrud\Service;

use Phpro\SmartCrud\Event\CrudEvent;

/**
 * Class DeleteService
 *
 * @package Phpro\SmartCrud\Service
 */
class DeleteService extends AbstractSmartService
{

    /**
     * @return bool
     */
    public function run($id, $data)
    {
        $em = $this->getEventManager();
        $entity = $this->loadEntity($id);

        $em->trigger($this->createEvent(CrudEvent::BEFORE_DELETE, $entity));

        $gateway = $this->getGateway();
        $result = $gateway->delete($this->loadEntity($id), $data);

        $em->trigger($this->createEvent(CrudEvent::AFTER_DELETE, $entity));

        return $result;
    }

}
