<?php

namespace spec\Phpro\SmartCrud\Service;

use Phpro\SmartCrud\Service\PaginatorServiceFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PaginatorServiceFactorySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Phpro\SmartCrud\Service\PaginatorServiceFactory');
    }

    public function it_should_have_default_config()
    {
        $this->getDefaultConfiguration()->shouldBeArray();
    }

    public function it_should_create_paginator()
    {
        $resultSet = array();
        $itemCount = 50;
        $pageNumber = 3;
        $queryKey = 'page';

        $options = array(
            PaginatorServiceFactory::CONFIG_ADAPTER_CLASS => '\Zend\Paginator\Adapter\ArrayAdapter',
            PaginatorServiceFactory::CONFIG_PAGE_SIZE => $itemCount,
            PaginatorServiceFactory::CONFIG_QUERY_KEY => $queryKey,
        );
        $params = array($queryKey => $pageNumber);

        $paginator = $this->createPaginator($resultSet, $options, $params);
        $paginator->shouldBeAnInstanceOf('Zend\Paginator\Paginator');
        $paginator->getCurrentPageNumber()->shouldReturn($pageNumber);
        $paginator->getItemCountPerPage()->shouldReturn($itemCount);
    }
}
