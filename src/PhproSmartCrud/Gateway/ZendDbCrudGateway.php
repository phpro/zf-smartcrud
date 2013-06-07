<?php

namespace PhproSmartCrud\Gateway;

/**
 * Class ZendDbCrudGateway
 *
 * @package PhproSmartCrud\Gateway
 */
class ZendDbCrudGateway extends  AbstractCrudGateway
{
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