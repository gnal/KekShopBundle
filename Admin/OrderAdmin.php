<?php

namespace Kek\ShopBundle\Admin;

use Msi\AdminBundle\Admin\Admin;
use Msi\AdminBundle\Grid\GridBuilder;
use Symfony\Component\Form\FormBuilder;

class OrderAdmin extends Admin
{
    public function configure()
    {
        $this->options = [
            'search_fields' => ['a.id', 'translations.name'],
            'order_by' => [
                'a.createdAt' => 'DESC',
            ],
        ];
    }

    public function buildGrid(GridBuilder $builder)
    {
        $builder
            // ->add('name')
            // ->add('price')
        ;
    }
}
