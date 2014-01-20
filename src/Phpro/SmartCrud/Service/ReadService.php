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
 * Class ReadService
 *
 * @package Phpro\SmartCrud\Service
 */
class ReadService extends AbstractSmartService
{

    /**
     * @return mixed
     */
    public function run($id, $data)
    {
        $result = $this->getResult();
        $em = $this->getEventManager();
        $em->trigger($this->createEvent(CrudEvent::BEFORE_READ, null));
        $entity = $this->loadEntity($id);
        $em->trigger($this->createEvent(CrudEvent::AFTER_READ, null));
        $result->setSuccess(true);
        $result->setEntity($entity);

        return $result;
    }
}
