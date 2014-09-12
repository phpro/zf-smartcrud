<?php


namespace Phpro\SmartCrud\Query;

interface QueryProviderAwareInterface
{

    /**
     * @param QueryProviderInterface $queryProvider
     */
    public function setQueryProvider($queryProvider);

    /**
     * @return QueryProviderInterface
     */
    public function getQueryProvider();

}
