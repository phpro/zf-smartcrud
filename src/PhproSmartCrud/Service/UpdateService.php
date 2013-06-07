<?php

namespace PhproSmartCrud\Service;

use PhproSmartCrud\Event\CrudEvent;

/**
 * Class UpdateService
 *
 * @package PhproSmartCrud\Service
 */
class UpdateService extends AbstractCrudActionService
{

    /**
     * @return bool
     */
    public function update()
    {
        $em = $this->getEventManager();
        $em->trigger($this->createEvent(CrudEvent::BEFORE_UPDATE));

        $crudService = $this->getCrudService();
        $gateway = $crudService->getGateway();
        $result = $gateway->update($crudService->getEntity(), $crudService->getParameters());

        $em->trigger($this->createEvent(CrudEvent::AFTER_UPDATE));
        return $result;
    }

}