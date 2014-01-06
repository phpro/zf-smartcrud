<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/phpro/zf-smartcrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace PhproSmartCrud\View\Strategy;


use PhproSmartCrud\View\Model\JsonModel;
use Zend\Form\Form;
use Zend\Http\PhpEnvironment\Response;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ModelInterface;

/**
 * Class JsonStrategy
 *
 * @package PhproSmartCrud\View\Strategy
 */
class JsonStrategy extends AbstractStrategy
{

    /**
     * @var int
     */
    protected $priority = 100000;

    /**
     * @param ModelInterface $model
     *
     * @return bool
     */
    protected function isValidModel($model)
    {
        return ($model instanceof JsonModel);
    }

    /**
     * @param MvcEvent       $e
     * @param ModelInterface $model
     *
     * @return Response|null
     */
    protected function renderModel(MvcEvent $e, ModelInterface $model)
    {
        $result = $model->getVariable('result', false);
        $form = $model->getVariable('form');

        $json = new \Zend\View\Model\JsonModel();
        $json->setVariable('result', $result);
        if (!$result && ($form instanceof Form)) {
            $json->setVariable('messages', $form->getMessages());
        }

        $e->setViewModel($json);
        return null;
    }

}
