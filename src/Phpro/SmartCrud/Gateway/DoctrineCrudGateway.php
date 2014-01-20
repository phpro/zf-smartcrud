<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/phpro/zf-smartcrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Phpro\SmartCrud\Gateway;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Phpro\SmartCrud\Exception\SmartCrudException;

/**
 * Class DoctrineCrudGateway
 *
 * @package Phpro\SmartCrud\Gateway
 */
class DoctrineCrudGateway
    implements ObjectManagerAwareInterface, CrudGatewayInterface
{

    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @param      $entityKey
     * @param null $id
     *
     * @return mixed
     */
    public function loadEntity($classNameOrEntity, $id = null)
    {
        if ($id) {
            $entity = $this->getRepository($classNameOrEntity)->find($id);
        } else {
            $rc = new \ReflectionClass($classNameOrEntity);
            $entity = $rc->newInstance();
        }

        return $entity;
    }

    /**
     * @param $entityClassName
     * @param $parameters
     *
     * @return array|\Traversable
     */
    public function getList($classNameOrEntity, $parameters)
    {
        return $this->getRepository($classNameOrEntity)->findAll();
    }

    /**
     * @param $entity
     * @param $parameters
     *
     * @return bool
     */
    public function create($entity, $parameters)
    {
        $em = $this->getObjectManager();
        try {
            $em->persist($entity);
            $em->flush();
        } catch (\Exception $e) {
            return false;
        }

        return true;
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
        $em = $this->getObjectManager();
        try {
            $em->persist($entity);
            $em->flush();
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * @param $entity
     * @param $id
     *
     * @return bool
     */
    public function delete($entity, $id)
    {
        try {
            $em = $this->getObjectManager();
            $em->remove($entity);
            $em->flush();
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $objectManager
     */
    public function setObjectManager(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @return array|ObjectManager|object
     * @throws \Phpro\SmartCrud\Exception\SmartCrudException
     */
    public function getObjectManager()
    {
        if (!$this->objectManager || !$this->objectManager instanceof ObjectManager) {
            throw new SmartCrudException('Invalid object manager configured.');
        }

        return $this->objectManager;
    }

    /**
     * @param $entity
     *
     * @return ObjectRepository
     */
    public function getRepository($classNameOrEntity)
    {
        $className = is_string($classNameOrEntity) ? $classNameOrEntity : get_class($classNameOrEntity);

        return $this->getObjectManager()->getRepository($className);
    }

}
