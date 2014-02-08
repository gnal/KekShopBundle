<?php

namespace Kek\ShopBundle\Doctrine;

use JMS\DiExtraBundle\Annotation as DI;

use Doctrine\Common\Persistence\ObjectManager;

/**
 * @DI\Service("kek_shop.manager.order_manager")
 */
class OrderManager
{
    protected $manager;
    protected $class;
    protected $repository;

    /**
     * @DI\InjectParams({
     *     "om" = @DI\Inject("doctrine.orm.entity_manager"),
     *     "class" = @DI\Inject("%kek_shop.order.class%")
     * })
     */
    public function __construct(ObjectManager $om, $class)
    {
        $this->manager = $om;
        $this->repository = $om->getRepository($class);
        $this->class = $class;
    }

    public function getClass()
    {
        return $this->class;
    }
}
