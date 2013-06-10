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