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
 * Class CreateService
 *
 * @package Phpro\SmartCrud\Service
 */
class CreateService extends AbstractSmartService
{

    /**
     * @param $id
     * @param array $data
     *
     * @return bool
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
                $em->trigger($this->createEvent(CrudEvent::BEFORE_CREATE, $entity));
                $result->setSuccess($this->getGateway()->create($entity, $data));
                $em->trigger($this->createEvent(CrudEvent::AFTER_CREATE, $entity));
            } else {

                $em->trigger($this->createEvent(CrudEvent::INVALID_CREATE, $form));
            }
        }

        $result->setEntity($entity);
        $result->setForm($form);
        return $result;
    }

}
