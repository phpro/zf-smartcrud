<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/veewee/PhproSmartCrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace PhproSmartCrud\Service;

use Zend\Mvc\Application;
use Zend\Mvc\Controller\AbstractController;
use Zend\Mvc\Controller\Plugin\Params;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ParametersService
 *
 * @package PhproSmartCrud\Service
 */
class ParametersService implements FactoryInterface
{
    /**
     * @var Params
     */
    protected $paramsPlugin = null;

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return $this
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var Application $app  */
        $app = $serviceLocator->get('application');
        $event = $app->getMvcEvent();
        $request = $event->getRequest();

        // Validate type of request is HTTP
        if (!($request instanceof \Zend\Http\Request)) {
            return $this;
        }

        // Validate controller
        $controller = $event->getController();
        if (!($controller instanceof AbstractController)) {
            return $this;
        }

        // Return params plugin
        $this->paramsPlugin = $controller->plugin('params');
        return $this;
    }

    /**
     * @param $method
     * @param $arguments
     *
     * @return mixed|null
     */
    protected function proxyToPlugin($method, $arguments)
    {
        if (!$this->paramsPlugin || !method_exists($this->paramsPlugin, $method)) {
            return null;
        }
        return call_user_func_array(array($this->paramsPlugin, $method), $arguments);
    }

    /**
     * @param null $name
     * @param null $default
     *
     * @return mixed|null
     */
    public function fromFiles($name = null, $default = null)
    {
        return $this->proxyToPlugin('fromFiles', array($name, $default));
    }

    /**
     * @param null $name
     * @param null $default
     *
     * @return mixed|null
     */
    public function fromHeader($name = null, $default = null)
    {
        return $this->proxyToPlugin('fromHeader', array($name, $default));
    }

    /**
     * @param null $name
     * @param null $default
     *
     * @return mixed|null
     */
    public function fromPost($name = null, $default = null)
    {
        return $this->proxyToPlugin('fromPost', array($name, $default));
    }

    /**
     * @param null $name
     * @param null $default
     *
     * @return mixed|null
     */
    public function fromQuery($name = null, $default = null)
    {
        return $this->proxyToPlugin('fromQuery', array($name, $default));
    }

    /**
     * @param null $name
     * @param null $default
     *
     * @return mixed|null
     */
    public function fromRoute($name = null, $default = null)
    {
        return $this->proxyToPlugin('fromRoute', array($name, $default));
    }

}
