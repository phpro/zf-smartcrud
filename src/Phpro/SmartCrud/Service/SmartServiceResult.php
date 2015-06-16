<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/phpro/zf-smartcrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Phpro\SmartCrud\Service;

/**
 * Class SmartServiceResult
 *
 * @package Phpro\SmartCrud\Service
 */
class SmartServiceResult
{
    /**
     * @var null|mixed
     */
    private $entity = null;

    /**
     * @var null|mixed
     */
    private $form = null;

    /**
     * @var array
     */
    private $list = array();

    /**
     * @var array
     */
    private $messages = array();

    /**
     * @param mixed|null $form
     */
    public function setForm($form)
    {
        $this->form = $form;

        return $this;
    }

    /**
     * @return mixed|null
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @var bool
     */
    private $success = false;

    /**
     * @param boolean $success
     */
    public function setSuccess($success)
    {
        $this->success = $success;

        return $this;
    }

    /**
     * @param mixed $entity
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param $list
     *
     * @return $this
     */
    public function setList($list)
    {
        $this->list = $list;

        return $this;
    }

    /**
     * @return array
     */
    public function getList()
    {
        return $this->list;
    }

    /**
     * @return bool
     */
    public function isSuccessFull()
    {
        return $this->success;
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @param $message
     * @return $this
     */
    public function addMessage($message)
    {
        $this->messages[] = $message;
        return $this;
    }

    /**
     * @param array $messages
     * @return $this
     */
    public function setMessages($messages)
    {
        $this->messages = $messages;
        return $this;
    }
}
