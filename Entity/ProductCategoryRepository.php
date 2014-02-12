<?php

namespace Kek\ShopBundle\Entity;

use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

class ProductCategoryRepository extends NestedTreeRepository
{
    public function findAllWithProducts()
    {
        $qb = $this->createQueryBuilder('a');

        $qb
            ->leftJoin('a.translations', 'translations')
            ->addSelect('translations')

            ->join('a.products', 'products')
            ->addSelect('products')

            ->leftJoin('products.translations', 'products_translations')
            ->addSelect('products_translations')

            ->andWhere($qb->expr()->eq('a.published', ':published'))
            ->setParameter('published', true)

            ->orderBy('translations.name', 'ASC')
        ;

        return $qb->getQuery()->execute();
    }
}
