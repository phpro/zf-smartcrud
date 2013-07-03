<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/veewee/PhproSmartCrud for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace spec\PhproSmartCrud\Gateway;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Prophecy\Prophet;

/**
 * Class DoctrineCrudGatewaySpec
 *
 * @package spec\PhproSmartCrud\Gateway
 */
class DoctrineCrudGatewaySpec extends AbstractCrudGatewaySpec
{

    public function it_is_initializable()
    {
        $this->shouldHaveType('PhproSmartCrud\Gateway\DoctrineCrudGateway');
    }

    protected function mockDoctrine()
    {
        // Create mocks
        $prophet = new Prophet();
        $serviceManager = $prophet->prophesize('\Zend\ServiceManager\ServiceManager');
        $entityManager = $prophet->prophesize('\Doctrine\ORM\EntityManager');
        $repository = $prophet->prophesize('Doctrine\ORM\EntityRepository');
        $entity = $prophet->prophesize('\stdClass');

        // Configure ServiceManager
        $this->setServiceManager($serviceManager);
        $serviceManager->has('Doctrine\ORM\EntityManager')->willReturn(true);
        $serviceManager->get('Doctrine\ORM\EntityManager')->willReturn($entityManager);

        // Configure Entitymanager
        $entityManager->getRepository(Argument::type('string'))->willReturn($repository);

        // Configure repository:
        $repository->findAll()->willReturn(array());
        $repository->find(Argument::any())->willReturn($entity);
    }


    public function it_should_extend_PhproSmartCrud_AbstractGateway()
    {
        $this->shouldBeAnInstanceOf('PhproSmartCrud\Gateway\AbstractCrudGateway');
    }

    /**
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     * @param \Doctrine\ORM\EntityManager $entityManager
     */
    public function it_should_have_doctrine_entity_manager($serviceManager, $entityManager)
    {
        $serviceManager->has('Doctrine\ORM\EntityManager')->willReturn(true);
        $serviceManager->get('Doctrine\ORM\EntityManager')->willReturn($entityManager);
        $this->getEntityManager()->shouldReturn($entityManager);
    }

    /**
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     * @param \Doctrine\ORM\EntityManager $entityManager
     */
    public function it_should_throw_smartCrudException_when_no_entity_manager_exists($serviceManager)
    {
        $serviceManager->has('Doctrine\ORM\EntityManager')->willReturn(false);
        $this->shouldThrow('\PhproSmartCrud\Exception\SmartCrudException')->duringGetEntityManager();
    }

    /**
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     * @param \Doctrine\ORM\EntityManager $entityManager
     * @param \Doctrine\ORM\EntityRepository $repository
     * @param \stdClass $entity
     */
    public function it_should_load_entity_repositories($serviceManager, $entityManager, $repository, $entity)
    {
        // Setup service manager
        $serviceManager->has('Doctrine\ORM\EntityManager')->willReturn(true);
        $serviceManager->get('Doctrine\ORM\EntityManager')->willReturn($entityManager);

        // Setup entityManager
        $entityManager->getRepository(Argument::type('string'))->willReturn($repository);

        // Run test
        $this->getRepository($entity)->shouldReturn($repository);
    }

    /**
     * @param \stdClass $entity
     */
    public function it_should_generate_list($entity)
    {
        $this->mockDoctrine();
        $params = array('filter1' => 'value1', 'filter2' => 'value2');
        $this->getList($entity, $params)->shouldReturn(array());
    }

    /**
     * @param \stdClass $entity
     * @TODO
     */
    public function it_should_create_entity($entity)
    {
        // TODO: find a better way to mock entityManager (shouldBeCalled instead of validating output)
    }

}
