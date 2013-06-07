<?php

namespace PhproSmartCrud\Gateway;

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
     * @return Repository
     */
    public function getRepository($entity)
    {
        return $this->getEntityManager()->getRepository(get_class($entity));
    }


}