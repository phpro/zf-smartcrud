<?php

namespace PhproSmartCrud\Service;

use PhproSmartCrud\Event\CrudEvent;

/**
 * Class ReadService
 *
 * @package PhproSmartCrud\Service
 */
class ReadService extends AbstractCrudActionService
{

    /**
     * @return mixed
     */
    public function read()
    {
        $em = $this->getEventManager();
        $em->trigger($this->createEvent(CrudEvent::BEFORE_READ));

        $crudService = $this->getCrudService();
        $gateway = $crudService->getGateway();
        $result = $gateway->read($crudService->getEntity(), $crudService->getParameters());

        $em->trigger($this->createEvent(CrudEvent::AFTER_READ));

        return $result;
    }

}