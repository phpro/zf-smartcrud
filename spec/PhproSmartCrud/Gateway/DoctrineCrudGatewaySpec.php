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

    public function it_should_extend_PhproSmartCrud_AbstractGateway()
    {
        $this->shouldBeAnInstanceOf('PhproSmartCrud\Gateway\AbstractCrudGateway');
    }

    /**
     * @param \Doctrine\ORM\EntityManager $entityManager Make sure to use the wrapped / revealed object
     */
    protected function mockEntityManager($entityManager)
    {
        // Create mocks
        $prophet = new Prophet();
        $serviceManager = $prophet->prophesize('\Zend\ServiceManager\ServiceManager');

        // Configure ServiceManager
        $this->setServiceManager($serviceManager);
        $serviceManager->has('Doctrine\ORM\EntityManager')->willReturn(true);
        $serviceManager->get('Doctrine\ORM\EntityManager')->willReturn($entityManager);
    }

    /**
     * @param \Doctrine\ORM\EntityRepository $repository Make sure to use the wrapped / revealed object
     */
    protected function mockEntityRepository($repository)
    {
        // Mock entity manager
        $prophet = new Prophet();
        $entityManager = $prophet->prophesize('Doctrine\ORM\EntityManager');
        $this->mockEntityManager($entityManager->reveal());

        // Mock repository
        $entityManager->getRepository(Argument::type('string'))->willReturn($repository);
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
     * @param \Doctrine\ORM\EntityRepository $repository
     * @param \stdClass $entity
     */
    public function it_should_load_entity_repositories($repository, $entity)
    {
        $this->mockEntityRepository($repository->getWrappedObject());

        // Load string
        $this->getRepository('stdClass')->shouldReturn($repository);

        // Load object
        $this->getRepository($entity)->shouldReturn($repository);
    }

    /**
     * @param \Doctrine\ORM\EntityRepository $repository
     */
    public function it_should_load_an_entity($repository, $entity)
    {
        $this->mockEntityRepository($repository->getWrappedObject());

        // With no ID
        $this->loadEntity('stdClass', null)->shouldBeAnInstanceOf('stdClass');

        // With ID
        $this->loadEntity('entityClass', 1);
        $repository->find(1)->shouldBeCalled();
    }

    /**
     * @param \Doctrine\ORM\EntityRepository $repository
     * @param \stdClass $entity
     */
    public function it_should_generate_list($repository, $entity)
    {
        $this->mockEntityRepository($repository->getWrappedObject());

        $this->getList($entity, array());
        $repository->findAll()->shouldBeCalled();
    }

    /**
     * @param \Doctrine\ORM\EntityManager $entityManager
     * @param \stdClass $entity
     */
    public function it_should_create_entity($entityManager, $entity)
    {
        $this->mockEntityManager($entityManager->getWrappedObject());

        $this->create($entity, array())->shouldReturn(true);

        $entityManager->persist($entity)->shouldBeCalled();
        $entityManager->flush()->shouldBeCalled();

    }

    /**
     * @param \Doctrine\ORM\EntityManager $entityManager
     * @param \stdClass $entity
     */
    public function it_should_not_create_invalid_entity($entityManager, $entity)
    {
        $this->mockEntityManager($entityManager->getWrappedObject());
        $entityManager->flush()->willThrow('\Exception');

        $this->create($entity, array())->shouldReturn(false);
    }

    /**
     * @param \Doctrine\ORM\EntityRepository $repository
     * @param \stdClass $entity
     */
    public function it_should_read_entity($repository, $entity)
    {
        $this->mockEntityRepository($repository->getWrappedObject());

        $this->read($entity, 1);
        $repository->find(1)->shouldBeCalled();
    }

    /**
     * @param \Doctrine\ORM\EntityManager $entityManager
     * @param \stdClass $entity
     */
    public function it_should_update_entity($entityManager, $entity)
    {
        $this->mockEntityManager($entityManager->getWrappedObject());

        $this->update($entity, array())->shouldReturn(true);

        $entityManager->persist($entity)->shouldBeCalled();
        $entityManager->flush()->shouldBeCalled();
    }

    /**
     * @param \Doctrine\ORM\EntityManager $entityManager
     * @param \stdClass $entity
     */
    public function it_should_not_update_invalid_entity($entityManager, $entity)
    {
        $this->mockEntityManager($entityManager->getWrappedObject());
        $entityManager->flush()->willThrow('\Exception');

        $this->update($entity, array())->shouldReturn(false);
    }


    /**
     * @param \Doctrine\ORM\EntityManager $entityManager
     * @param \stdClass $entity
     */
    public function it_should_delete_entity($entityManager, $entity)
    {
        $this->mockEntityManager($entityManager->getWrappedObject());

        $this->delete($entity, array())->shouldReturn(true);

        $entityManager->remove($entity)->shouldBeCalled();
        $entityManager->flush()->shouldBeCalled();
    }

    /**
     * @param \Doctrine\ORM\EntityManager $entityManager
     * @param \stdClass $entity
     */
    public function it_should_not_delete_invalid_entity($entityManager, $entity)
    {
        $this->mockEntityManager($entityManager->getWrappedObject());
        $entityManager->flush()->willThrow('\Exception');

        $this->delete($entity, array())->shouldReturn(false);
    }

}
