<?php

namespace Phpro\SmartCrud\Service;

/**
 * Class PaginatorFactoryAwareInterface
 *
 * @package Phpro\SmartCrud\Service
 */
interface PaginatorFactoryAwareInterface
{

    /**
     * @param PaginatorServiceFactory $paginatorFactory
     *
     * @return mixed
     */
    public function setPaginatorFactory($paginatorFactory);

    /**
     * @return PaginatorServiceFactory
     */
    public function getPaginatorFactory();

}
