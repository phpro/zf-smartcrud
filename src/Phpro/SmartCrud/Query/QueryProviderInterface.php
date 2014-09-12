<?php

namespace Phpro\SmartCrud\Query;

/**
 * Interface ProviderInterface
 *
 * @package Phpro\SmartCrud\Query
 */
interface QueryProviderInterface
{

    /**
     * This method creates a query
     *
     * @param $data
     * @return mixed
     */
    public function createQuery($data);

}
