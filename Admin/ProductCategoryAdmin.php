<?php

namespace Kek\ShopBundle\Admin;

use Msi\AdminBundle\Admin\Admin;
use Msi\AdminBundle\Grid\GridBuilder;
use Symfony\Component\Form\FormBuilder;

class ProductCategoryAdmin extends Admin
{
    public function configure()
    {
        $this->options = [
            'search_fields' => ['a.id', 'translations.name'],
        ];
    }

    public function buildGrid(GridBuilder $builder)
    {
        $builder
            ->add('published', 'boolean')
            ->add('name', 'tree')
        ;
    }

    public function buildForm(FormBuilder $builder)
    {
        $builder
            ->add('published', 'checkbox')
        ;
    }

    public function buildTranslationForm(FormBuilder $builder)
    {
        $builder
            ->add('name')
        ;
    }
}
