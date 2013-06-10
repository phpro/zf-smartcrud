<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/veewee/PhproSmartCrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace PhproSmartCrud\Gateway;

use Doctrine\ORM\EntityManager;
use Doctrine\EntityRepository\EntityRepository;

/**
 * Class DoctrineCrudGateway
 *
 * @package PhproSmartCrud\Gateway
 */
class DoctrineCrudGateway extends  AbstractCrudGateway
{
    /**
     * @param $entity
     * @param $parameters
     *
     * @return array|\Traversable
     */
    public function getList($entity, $parameters)
    {
        return $this->getRepository($entity)->findAll();
    }

    /**
     * @param $entity
     * @param $parameters
     *
     * @return bool
     */
    public function create($entity, $parameters)
    {
        $em = $this->getEntityManager();
        $em->persist($entity);
        $em->flush();
    }

    /**
     * @param $entity
     * @param $id
     *
     * @return mixed
     */
    public function read($entity, $id)
    {
        return $this->getRepository($entity)->find($id);

    }

    /**
     * @param $entity
     * @param $parameters
     *
     * @return bool
     */
    public function update($entity, $parameters)
    {
        $em = $this->getEntityManager();
        $em->persist($entity);
        $em->flush();
    }

    /**
     * @param $entity
     * @param $id
     *
     * @return bool
     */
    public function delete($entity, $id)
    {
        $em = $this->getEntityManager();
        $em->remove($entity);
        $em->flush();
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->getServiceManager()->get('Doctrine\ORM\EntityManager');
    }

    /**
     * @param $entity
     *
     * @return EntityRepository
     */
    public function getRepository($entity)
    {
        return $this->getEntityManager()->getRepository(get_class($entity));
    }


}