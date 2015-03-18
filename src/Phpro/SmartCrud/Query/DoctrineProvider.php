<?php


namespace Phpro\SmartCrud\Query;

use Doctrine\Common\Persistence\ObjectRepository;

/**
 * Class DoctrineProvider
 *
 * @package Phpro\SmartCrud\Query
 */
class DoctrineProvider implements QueryProviderInterface
{
    /**
     * @var ObjectRepository
     */
    protected $repository;

    /**
     * @var mixed
     */
    protected $alias;

    /**
     * @param $repository
     * @param $alias
     */
    public function __construct($repository, $alias = null)
    {
        $this->repository = $repository;
        $this->alias = $alias;
    }

    /**
     * @inheritdoc
     */
    public function createQuery($data)
    {
        $qb = $this->repository->createQueryBuilder($this->alias);
        $query = $qb->getQuery();
        return $query;
    }
}
