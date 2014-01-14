<?php
/**
 * Smartcrud for Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/phpro/zf-smartcrud for the canonical source repository
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
class DoctrineCrudGatewaySpec extends ObjectBehavior
{

    public function it_is_initializable()
    {
        $this->shouldHaveType('PhproSmartCrud\Gateway\DoctrineCrudGateway');
    }

    public function it_should_implement_PhproSmartCrud_CrudGatewayInterface()
    {
        $this->shouldImplement('PhproSmartCrud\Gateway\CrudGatewayInterface');
    }

    public function it_should_implement_Doctrine_ObjectManagerAwareInterface()
    {
        $this->shouldImplement('DoctrineModule\Persistence\ObjectManagerAwareInterface');
    }

    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $objectManager Make sure to use the wrapped / revealed object
     */
    public function let($objectManager)
    {
        $this->setObjectManager($objectManager);
    }

    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $objectManager
     */
    public function it_should_have_doctrine_object_manager($objectManager)
    {
        $this->getObjectManager()->shouldReturn($objectManager);
    }

    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $objectManager
     * @param \Doctrine\Common\Persistence\ObjectRepository $repository
     * @param \stdClass $entity
     */
    public function it_should_load_object_repositories($objectManager, $repository, $entity)
    {
        $objectManager->getRepository(Argument::type('string'))->willReturn($repository);

        // Load string
        $this->getRepository('stdClass')->shouldReturn($repository);

        // Load object
        $this->getRepository($entity)->shouldReturn($repository);
    }

    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $objectManager
     * @param \Doctrine\Common\Persistence\ObjectRepository $repository
     */
    public function it_should_load_an_entity($objectManager, $repository, $entity)
    {
        $objectManager->getRepository(Argument::type('string'))->willReturn($repository);

        // With no ID
        $this->loadEntity('stdClass', null)->shouldBeAnInstanceOf('stdClass');

        // With ID
        $this->loadEntity('entityClass', 1);
        $repository->find(1)->shouldBeCalled();
    }

    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $objectManager
     * @param \Doctrine\Common\Persistence\ObjectRepository $repository
     * @param \stdClass $entity
     */
    public function it_should_generate_list($objectManager, $repository, $entity)
    {
        $objectManager->getRepository(Argument::type('string'))->willReturn($repository);

        $this->getList($entity, array());
        $repository->findAll()->shouldBeCalled();
    }

    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $objectManager
     * @param \stdClass $entity
     */
    public function it_should_create_entity($objectManager, $entity)
    {
        $this->create($entity, array())->shouldReturn(true);

        $objectManager->persist($entity)->shouldBeCalled();
        $objectManager->flush()->shouldBeCalled();
    }

    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $objectManager
     * @param \stdClass $entity
     */
    public function it_should_not_create_invalid_entity($objectManager, $entity)
    {
        $objectManager->flush()->willThrow('\Exception');

        $this->create($entity, array())->shouldReturn(false);
    }

    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $objectManager
     * @param \Doctrine\Common\Persistence\ObjectRepository $repository
     * @param \stdClass $entity
     */
    public function it_should_read_entity($objectManager, $repository, $entity)
    {
        $objectManager->getRepository(Argument::type('string'))->willReturn($repository);

        $this->read($entity, 1);
        $repository->find(1)->shouldBeCalled();
    }

    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $objectManager
     * @param \stdClass $entity
     */
    public function it_should_update_entity($objectManager, $entity)
    {
        $this->update($entity, array())->shouldReturn(true);

        $objectManager->persist($entity)->shouldBeCalled();
        $objectManager->flush()->shouldBeCalled();
    }

    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $objectManager
     * @param \stdClass $entity
     */
    public function it_should_not_update_invalid_entity($objectManager, $entity)
    {
        $objectManager->flush()->willThrow('\Exception');

        $this->update($entity, array())->shouldReturn(false);
    }


    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $objectManager
     * @param \stdClass $entity
     */
    public function it_should_delete_entity($objectManager, $entity)
    {
        $this->delete($entity, array())->shouldReturn(true);

        $objectManager->remove($entity)->shouldBeCalled();
        $objectManager->flush()->shouldBeCalled();
    }

    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $objectManager
     * @param \stdClass $entity
     */
    public function it_should_not_delete_invalid_entity($objectManager, $entity)
    {
        $objectManager->flush()->willThrow('\Exception');

        $this->delete($entity, array())->shouldReturn(false);
    }

}
