<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/phpro/zf-smartcrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Phpro\SmartCrud\Service;

use Phpro\SmartCrud\Event\CrudEvent;
use Phpro\SmartCrud\Exception\SmartCrudException;
use Phpro\SmartCrud\Query\QueryProviderAwareInterface;
use Phpro\SmartCrud\Query\QueryProviderInterface;
use Zend\Paginator\Paginator;

/**
 * Class ListService
 *
 * @package Phpro\SmartCrud\Service
 */
class ListService extends AbstractSmartService
    implements
    PaginatorFactoryAwareInterface,
    QueryProviderAwareInterface
{

    /**
     * @var PaginatorServiceFactory
     */
    protected $paginatorFactory;

    /**
     * @var QueryProviderInterface
     */
    protected $queryProvider;

    /**
     * @param PaginatorServiceFactory $paginatorFactory
     *
     * @return mixed|void
     */
    public function setPaginatorFactory($paginatorFactory)
    {
        $this->paginatorFactory = $paginatorFactory;
    }

    /**
     * @return PaginatorServiceFactory
     */
    public function getPaginatorFactory()
    {
        return $this->paginatorFactory;
    }

    /**
     * @param \Phpro\SmartCrud\Query\QueryProviderInterface $queryProvider
     */
    public function setQueryProvider($queryProvider)
    {
        $this->queryProvider = $queryProvider;
    }

    /**
     * @return \Phpro\SmartCrud\Query\QueryProviderInterface
     */
    public function getQueryProvider()
    {
        return $this->queryProvider;
    }

    /**
     * @param int                $id
     * @param array|\Traversable $data
     *
     * @return SmartServiceResult
     */
    public function run($id, $data)
    {

        $result = $this->getResult();
        $em = $this->getEventManager();
        $em->trigger($this->createEvent(CrudEvent::BEFORE_LIST, null));

        $gateway = $this->getGateway();
        $records = $gateway->getList($this->getEntityKey(), $data, $this->getQueryProvider());
        $paginator = $this->getPaginator($records, $data);

        $em->trigger($this->createEvent(CrudEvent::AFTER_LIST, null));
        $result->setSuccess(true);
        $result->setList($paginator);

        return $result;
    }

    /**
     * @param $records
     * @param $data
     *
     * @return Paginator
     * @throws \Phpro\SmartCrud\Exception\SmartCrudException
     */
    public function getPaginator($records, $data)
    {

        $options = $this->getOptions();
        if (!isset($options['paginator'])) {
            throw new SmartCrudException('The CRUD list service needs paginator configuration.');
        }

        $paginatorOptions = $options['paginator'];
        $factory = $this->getPaginatorFactory();
        $paginator = $factory->createPaginator($records, $paginatorOptions, $data);
        return $paginator;
    }
}
