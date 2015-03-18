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
     * @param $repository
     */
    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    /**
     * @inheritdoc
     */
    public function createQuery($data)
    {
        $qb = $this->repository->createQueryBuilder('obj');
        $query = $qb->getQuery();
        return $query;
    }
}
