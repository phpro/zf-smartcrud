<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/phpro/zf-smartcrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Phpro\SmartCrud\Gateway;

use Phpro\SmartCrud\Query\QueryProviderInterface;

/**
 * Class CrudGatewayInterface
 *
 * @package Phpro\SmartCrud\Gateway
 */
interface CrudGatewayInterface
{
    /**
     * @param      $entityKey
     * @param null $id
     *
     * @return mixed
     */
    public function loadEntity($entityKey, $id = null);

    /**
     * @param $entity
     * @param $parameters
     * @param QueryProviderInterface $queryProvider
     *
     * @return array|\Traversable
     */
    public function getList($entity, $parameters, $queryProvider = null);

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
