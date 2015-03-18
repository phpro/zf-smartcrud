<?php

namespace spec\Phpro\SmartCrud\Query;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DoctrineProviderSpec extends ObjectBehavior
{
    /**
     * @param \Doctrine\ORM\EntityRepository $repository
     */
    public function let($repository)
    {
        $this->beConstructedWith($repository);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Phpro\SmartCrud\Query\DoctrineProvider');
    }

    public function it_should_be_a_queryProvider()
    {
        $this->shouldImplement('Phpro\SmartCrud\Query\QueryProviderInterface');
    }

    /**
     * @param \Doctrine\ORM\EntityRepository $repository
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder
     * @param \Doctrine\ORM\Query Query $query
     */
    public function it_should_create_a_list($repository, $queryBuilder, $query)
    {
        $repository->createQueryBuilder('obj')->willReturn($queryBuilder);
        $queryBuilder->getQuery()->willReturn($query);

        $this->createQuery(array())->shouldReturn($query);
    }
}
