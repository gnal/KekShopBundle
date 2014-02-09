<?php

namespace Kek\ShopBundle\Entity;

use Doctrine\ORM\EntityRepository;

class OrderRepository extends EntityRepository
{
    public function findCurrent($user, $request)
    {
        $qb = $this->createQueryBuilder('a');

        if ($user) {
            $qb
                ->andWhere($qb->expr()->eq('a.user', ':user'))
                ->setParameter('user', $user)
            ;
        } else {
            $qb
                ->andWhere($qb->expr()->eq('a.id', ':id'))
                ->setParameter('id', $request->cookies->get('msci'))
            ;
            $qb->andWhere($qb->expr()->isNull('a.user'));
        }

        $qb->andWhere($qb->expr()->isNull('a.frozenAt'));

        return $qb->getQuery()->getOneOrNullResult();
    }
}
