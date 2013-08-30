<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/veewee/PhproSmartCrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace PhproSmartCrud\Gateway;

/**
 * Class ZendDbCrudGateway
 *
 * @package PhproSmartCrud\Gateway
 */
class ZendDbCrudGateway extends  AbstractCrudGateway
{

    /**
     * @param      $entityKey
     * @param null $id
     *
     * @return mixed
     */
    public function loadEntity($entityKey, $id = null)
    {
        // TODO: Implement getEntity() method.
    }

    /***
     * @param $entity
     * @param $parameters
     * @return array|\Traversable|void
     */
    public function getList($entity, $parameters)
    {
        // TODO: Implement getList() method.
    }

    /**
     * @param $entity
     * @param $parameters
     *
     * @return bool
     */
    public function create($entity, $parameters)
    {
        // TODO: Implement create() method.
    }

    /**
     * @param $entity
     * @param $id
     *
     * @return mixed
     */
    public function read($entity, $id)
    {
        // TODO: Implement read() method.
    }

    /**
     * @param $entity
     * @param $parameters
     *
     * @return bool
     */
    public function update($entity, $parameters)
    {
        // TODO: Implement update() method.
    }

    /**
     * @param $entity
     * @param $id
     *
     * @return bool
     */
    public function delete($entity, $id)
    {
        // TODO: Implement delete() method.
    }


}
