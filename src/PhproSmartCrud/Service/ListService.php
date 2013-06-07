<?php

namespace PhproSmartCrud\Service;

use PhproSmartCrud\Event\CrudEvent;

/**
 * Class ListService
 *
 * @package PhproSmartCrud\Service
 */
class ListService extends AbstractCrudActionService
{

    /**
     * @return array|\Traversable
     */
    public function getList()
    {
        $em = $this->getEventManager();
        $em->trigger($this->createEvent(CrudEvent::BEFORE_LIST));

        $crudService = $this->getCrudService();
        $gateway = $crudService->getGateway();
        $return = $gateway->getList($crudService->getEntity(), $crudService->getParameters());

        $em->trigger($this->createEvent(CrudEvent::AFTER_LIST));

        return $return;
    }

}