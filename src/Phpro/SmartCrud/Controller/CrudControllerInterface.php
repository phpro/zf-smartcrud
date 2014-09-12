<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/phpro/zf-smartcrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Phpro\SmartCrud\Controller;

use Phpro\SmartCrud\Service\SmartServiceInterface;
use Phpro\SmartCrud\View\Model\ViewModelBuilder;
/**
 * Class CrudControllerInterface
 *
 * @package Phpro\SmartCrud\Controller
 */
interface CrudControllerInterface
{
    /**
     * @param string $name
     *
     * @return $this
     */
    public function setIdentifierName($name);

    /**
     * @param SmartServiceInterface $service
     *
     * @return $this
     */
    public function setSmartService(SmartServiceInterface $service);

    /**
     * @param 'Phpro\SmartCrud\View\Model\ViewModelBuilder $viewModelBuilder
     *
     * @return $this
     */
    public function setViewModelBuilder(ViewModelBuilder $viewModelBuilder);
}
