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
use Phpro\SmartCrud\Query\DoctrineProvider;
use Phpro\SmartCrud\Query\QueryProviderInterface;

/**
 * Class DoctrineCrudGateway
 *
 * @package Phpro\SmartCrud\Gateway
 */
class DoctrineCrudGateway
    implements ObjectManagerAwareInterface, CrudGatewayInterface
{
    const TYPE_ORM = 'ORM';
    const TYPE_ODM = 'ODM';

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
     * @param QueryProviderInterface $queryProvider
     *
     * @return array|\Traversable
     * @throws \RuntimeException
     */
    public function getList($classNameOrEntity, $parameters, $queryProvider = null)
    {
        $omType = $this->getObjectManagerType();

        if (!$queryProvider) {
            $repository = $this->getRepository($classNameOrEntity);
            $alias = ($omType === $this::TYPE_ORM) ? 'e' : null;
            $queryProvider = new DoctrineProvider($repository, $alias);
        }

        $query = $queryProvider->createQuery($parameters);

        // Return results based on type of object manager
        if ($omType === $this::TYPE_ORM) {
            return $query->getResult();
        }
        if ($omType === $this::TYPE_ODM) {
            return $query->execute()->toArray();
        }
        return array();
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
     * @return bool
     * @throws \Exception
     */
    public function delete($entity, $id)
    {
        try {
            $em = $this->getObjectManager();
            $em->remove($entity);
            $em->flush();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
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


    /**
     * @return string
     */
    protected function getObjectManagerType()
    {
        if ($this->objectManager instanceof \Doctrine\ORM\EntityManager) {
            return $this::TYPE_ORM;
        }

        if ($this->objectManager instanceof \Doctrine\ODM\MongoDB\DocumentManager) {
            return $this::TYPE_ODM;
        }

        throw new \RuntimeException('Unsupported type of object-manager.');
    }
}
