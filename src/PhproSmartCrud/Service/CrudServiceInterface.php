<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/veewee/PhproSmartCrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */
namespace PhproSmartCrud\Service;

interface CrudServiceInterface
{

    /**
     * @param int $id
     * @param array|\Traversable $data
     *
     * @return mixed
     */
    public function run($id, $data);

    /**
     * @param string $entityKey
     *
     * @return $this
     */
    public function setEntityKey($entityKey);

    /**
     * @param array $parameters
     *
     * @return $this
     */
    public function setParameters($parameters);

    /**
     * @param \Zend\Form\Form $form
     *
     * @return $this
     */
    public function setForm($form);

    /**
     * @param \PhproSmartCrud\Gateway\CrudGatewayInterface $gateway
     *
     * @return $this
     */
    public function setGateway($gateway);

    /**
     * @return \Zend\EventManager\EventManager
     */
    public function getEventManager();

}
