<?php

namespace PhproSmartCrud\Service;

use PhproSmartCrud\Event\CrudEvent;

/**
 * Class DeleteService
 *
 * @package PhproSmartCrud\Service
 */
class DeleteService extends AbstractCrudActionService
{

    /**
     * @return bool
     */
    public function delete()
    {
        $em = $this->getEventManager();
        $em->trigger($this->createEvent(CrudEvent::BEFORE_DELETE));

        $crudService = $this->getCrudService();
        $gateway = $crudService->getGateway();
        $result = $gateway->delete($crudService->getEntity(), $crudService->getParameters());

        $em->trigger($this->createEvent(CrudEvent::AFTER_DELETE));

        return $result;
    }

}