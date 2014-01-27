<?php

namespace Phpro\SmartCrud\Service;

use Phpro\SmartCrud\Exception\InvalidArgumentException;
use Zend\Paginator\Paginator;

/**
 * Class PaginatorServiceFactory
 *
 * @package Phpro\SmartCrud\Service
 */
class PaginatorServiceFactory
{

    const CONFIG_ADAPTER_CLASS = 'adapter_class';
    const CONFIG_PAGE_SIZE = 'page_size';
    const CONFIG_QUERY_KEY = 'query_key';

    /**
     * @return array
     */
    public function getDefaultConfiguration()
    {
        return array(
            self::CONFIG_PAGE_SIZE => 50,
            self::CONFIG_QUERY_KEY => 'page',
        );
    }

    /**
     * @param $resultSet
     * @param $options
     * @param $params
     *
     * @return Paginator
     * @throws \Phpro\SmartCrud\Exception\InvalidArgumentException
     */
    public function createPaginator($resultSet, $options, $params)
    {
        $options = array_merge($this->getDefaultConfiguration(), $options);

        if (!$options[self::CONFIG_ADAPTER_CLASS] ) {
            throw new InvalidArgumentException('No paginator adapter class configured.');
        }

        $adapterClass = $options[self::CONFIG_ADAPTER_CLASS];
        $pageSize = intval($options[self::CONFIG_PAGE_SIZE]);
        $pageKey = $options[self::CONFIG_QUERY_KEY];
        $pageNumber = isset($params[$pageKey]) ? intval($params[$pageKey]) : 1;

        if (!class_exists($adapterClass)) {
            throw new InvalidArgumentException(sprintf('Invalid paginator adapter class %s'), $adapterClass);
        }

        $rc = new \ReflectionClass($adapterClass);
        if (!$rc->implementsInterface('Zend\Paginator\Adapter\AdapterInterface')) {
            throw new InvalidArgumentException(sprintf('The paginator adapter class %s does not implement AdapterInterface', $adapterClass));
        }

        /** @var \Zend\Paginator\Adapter\AdapterInterface  $adapter */
        $adapter = $rc->newInstance($resultSet);


        $paginator = new Paginator($adapter);
        $paginator->setCurrentPageNumber($pageNumber);
        $paginator->setItemCountPerPage($pageSize);

        return $paginator;
    }

}
