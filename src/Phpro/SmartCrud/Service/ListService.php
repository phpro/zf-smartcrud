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
 * Class ListService
 *
 * @package Phpro\SmartCrud\Service
 */
class ListService extends AbstractSmartService
{

    /**
     * @param int                $id
     * @param array|\Traversable $data
     *
     * @return SmartServiceResult
     */
    public function run($id, $data)
    {

        $result = $this->getResult();
        $em = $this->getEventManager();
        $em->trigger($this->createEvent(CrudEvent::BEFORE_LIST, null));

        $gateway = $this->getGateway();
        $records = $gateway->getList($this->getEntityKey(), $data);

        $em->trigger($this->createEvent(CrudEvent::AFTER_LIST, null));
        $result->setSuccess(true);
        $result->setList($records);

        return $result;
    }
}
