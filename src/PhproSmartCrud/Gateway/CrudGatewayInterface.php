<?php
namespace PhproSmartCrud\Gateway;

/**
 * Class CrudGatewayInterface
 *
 * @package PhproSmartCrud\Gateway
 */
interface CrudGatewayInterface
{

    /**
     * @param $entity
     * @param $parameters
     *
     * @return array|\Traversable
     */
    public function getList($entity, $parameters);

    /**
     * @param $entity
     * @param $parameters
     *
     * @return bool
     */
    public function create($entity, $parameters);

    /**
     * @param $entity
     * @param $id
     *
     * @return mixed
     */
    public function read($entity, $id);

    /**
     * @param $entity
     * @param $parameters
     *
     * @return bool
     */
    public function update($entity, $parameters);

    /**
     * @param $entity
     * @param $id
     *
     * @return bool
     */
    public function delete($entity, $id);

}