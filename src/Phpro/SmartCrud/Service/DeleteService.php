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
     * @param null $id
     * @param null $data
     *
     * @return SmartServiceResult
     */
    public function run($id = null, $data = null)
    {
        $em = $this->getEventManager();
        $entity = $this->loadEntity($id);

        $result = $this->getResult();
        $result->setEntity($entity);

        if (!$entity) {
            $em->trigger($this->createEvent(CrudEvent::INVALID_DELETE, $entity));
            $result->setSuccess(false);
            return $result;
        }

        $em->trigger($this->createEvent(CrudEvent::BEFORE_DELETE, $entity));
        $deleted = $this->getGateway()->delete($entity, $data);
        $em->trigger($this->createEvent(CrudEvent::AFTER_DELETE, $entity));

        $result->setSuccess($deleted);
        return $result;
    }
}
