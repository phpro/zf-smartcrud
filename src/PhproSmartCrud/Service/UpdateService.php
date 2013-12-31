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
 * Class UpdateService
 *
 * @package PhproSmartCrud\Service
 */
class UpdateService extends AbstractCrudService
{

    /**
     * @return bool
     */
    public function run($id, $data)
    {
        $em = $this->getEventManager();
        $entity = $this->loadEntity($id);
        $form = $this->getForm($entity)->setData($data);
        $em->trigger($this->createEvent(CrudEvent::BEFORE_DATA_VALIDATION, $form));
        if($form->isValid()) {
            $em->trigger($this->createEvent(CrudEvent::BEFORE_UPDATE, $entity));
            $gateway = $this->getGateway();
            $result = $gateway->update($this->loadEntity($id), $data);

            $em->trigger($this->createEvent(CrudEvent::AFTER_UPDATE, $entity));
        } else {
            $em->trigger($this->createEvent(CrudEvent::INVALID_UPDATE, $form));
            $result = false;
        }

        return $result;
    }

}
