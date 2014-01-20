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
        $result = $this->getResult();
        $em = $this->getEventManager();
        $entity = $this->loadEntity($id);
        $form = $this->getForm($entity);
        if ($data === null) {
            $result->setSuccess(true);
        } else {
            $form->setData($data);
            $em->trigger($this->createEvent(CrudEvent::BEFORE_DATA_VALIDATION, $form, ['postData' => $data]));
            if ($form->isValid()) {
                $em->trigger($this->createEvent(CrudEvent::BEFORE_DELETE, $entity));
                $result->setSuccess($this->getGateway()->delete($entity, $data));
                $em->trigger($this->createEvent(CrudEvent::AFTER_DELETE, $entity));
            } else {

                $em->trigger($this->createEvent(CrudEvent::INVALID_DELETE, $form));
            }
        }

        $result->setEntity($entity);
        $result->setForm($form);
        return $result;
    }

}
