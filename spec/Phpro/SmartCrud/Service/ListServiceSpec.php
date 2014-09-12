<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/phpro/zf-smartcrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace spec\Phpro\SmartCrud\Service;

use Phpro\SmartCrud\Event\CrudEvent;
use Prophecy\Argument;

/**
 * Class ListServiceSpec
 *
 * @package spec\Phpro\SmartCrud\Service
 */
class ListServiceSpec extends AbstractSmartServiceSpec
{

    /**
     * @param \Phpro\SmartCrud\Gateway\CrudGatewayInterface $gateway
     * @param \Zend\EventManager\EventManager               $eventManager
     * @param \Zend\Form\Form                               $form
     * @param \Phpro\SmartCrud\Query\QueryProviderInterface $queryProvider
     */
    public function let($gateway, $eventManager, $entity, $queryProvider)
    {
        parent::let($gateway, $eventManager, $entity);

        $this->setQueryProvider($queryProvider);
        $this->setOptions(array(
            'paginator' => array(),
        ));
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Phpro\SmartCrud\Service\ListService');
    }

    public function it_should_extend_Phpro_SmartCrud_AbstractSmartService()
    {
        $this->shouldBeAnInstanceOf('Phpro\SmartCrud\Service\AbstractSmartService');
    }

    public function it_should_implement_paginatorFactoryAwareInterface()
    {
        $this->shouldImplement('\Phpro\SmartCrud\Service\PaginatorFactoryAwareInterface');
    }

    public function it_should_implement_queryProviderAwareInterface()
    {
        $this->shouldImplement('\Phpro\SmartCrud\Query\QueryProviderAwareInterface');
    }

    /**
     * @param \Phpro\SmartCrud\Service\PaginatorServiceFactory $paginatorFactory
     */
    public function it_should_have_paginatorFactory($paginatorFactory)
    {
        $this->setPaginatorFactory($paginatorFactory);
        $this->getPaginatorFactory()->shouldReturn($paginatorFactory);
    }

    /**
     * @param \Phpro\SmartCrud\Query\QueryProviderInterface $queryProvider
     */
    public function it_should_have_a_query_provider($queryProvider)
    {
        $this->setQueryProvider($queryProvider);
        $this->getQueryProvider()->shouldReturn($queryProvider);
    }

    /**
     * @param \Phpro\SmartCrud\Gateway\CrudGatewayInterface $gateway
     * @param \Zend\EventManager\EventManager               $eventManager
     * @param \Phpro\SmartCrud\Service\PaginatorServiceFactory $paginatorFactory
     * @param \Phpro\SmartCrud\Service\SmartServiceResult   $result
     * @param \Zend\Paginator\Paginator $paginator
     * @param \Phpro\SmartCrud\Query\QueryProviderInterface $queryProvider
     */
    public function it_should_return_a_result($gateway, $eventManager, $paginatorFactory, $result, $paginator, $queryProvider)
    {
        $getData = array();
        $list = array();
        $gateway->getList('entityKey', $getData, $queryProvider)->willReturn($list);

        $paginatorFactory->createPaginator($list, Argument::cetera())->willReturn($paginator);
        $this->setPaginatorFactory($paginatorFactory);

        $result->setSuccess(Argument::any())->shouldBeCalled();
        $result->setForm(Argument::any())->shouldNotBeCalled();
        $result->setList($paginator)->shouldBeCalled();

        $this->setEntityKey('entityKey');
        $this->setGateway($gateway);
        $this->setResult($result);

        $this->run(Argument::any(), $getData)->shouldReturn($result);;
        $eventManager->trigger(Argument::which('getName', CrudEvent::BEFORE_LIST))->shouldBeCalled();
        $eventManager->trigger(Argument::which('getName', CrudEvent::AFTER_LIST))->shouldBeCalled();
    }

    /**
     * @param \Phpro\SmartCrud\Service\PaginatorServiceFactory $paginatorFactory
     * @param \Zend\Paginator\Paginator $paginator
     */
    public function it_should_create_paginator($paginatorFactory, $paginator)
    {
        $records = array();
        $params = array();
        $paginatorFactory->createPaginator($records, Argument::any(), $params)->willReturn($paginator);
        $this->setPaginatorFactory($paginatorFactory);

        $this->getPaginator($records, $params)->shouldReturn($paginator);
    }

}
