<?php

namespace PhproSmartCrud\Service;

use PhproSmartCrud\Event\CrudEvent;

/**
 * Class CreateService
 *
 * @package PhproSmartCrud\Service
 */
class CreateService extends AbstractCrudActionService
{

    /**
     * @return bool
     */
    public function create()
    {
        $em = $this->getEventManager();
        $em->trigger($this->createEvent(CrudEvent::BEFORE_CREATE));

        $crudService = $this->getCrudService();
        $gateway = $crudService->getGateway();
        $result = $gateway->create($crudService->getEntity(), $crudService->getParameters());

        $em->trigger($this->createEvent(CrudEvent::AFTER_CREATE));

        return $result;
    }

}