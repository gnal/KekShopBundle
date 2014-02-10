<?php

namespace Kek\ShopBundle\Entity;

use Doctrine\ORM\EntityRepository;

class OrderRepository extends EntityRepository
{
    public function findCurrentByUser($user)
    {
        $qb = $this->createQueryBuilder('a');

        $qb
            ->andWhere($qb->expr()->eq('a.user', ':user'))
            ->setParameter('user', $user)
        ;

        $qb->andWhere($qb->expr()->isNull('a.frozenAt'));

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function findCurrentById($id)
    {
        $qb = $this->createQueryBuilder('a');

        $qb
            ->andWhere($qb->expr()->eq('a.id', ':id'))
            ->setParameter('id', $id)
        ;
        // a cookie order should never have a user or else you could potentially steal people's orders
        $qb->andWhere($qb->expr()->isNull('a.user'));

        $qb->andWhere($qb->expr()->isNull('a.frozenAt'));

        return $qb->getQuery()->getOneOrNullResult();
    }
}
