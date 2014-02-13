<?php

namespace Kek\ShopBundle\Admin;

use Msi\AdminBundle\Admin\Admin;
use Msi\AdminBundle\Grid\GridBuilder;
use Symfony\Component\Form\FormBuilder;

class TaxAdmin extends Admin
{
    public function configure()
    {
        $this->options = [
            'search_fields' => ['a.id', 'translations.name'],
            'form_template' => 'KekShopBundle:Tax/Admin:form.html.twig',
        ];
    }

    public function buildGrid(GridBuilder $builder)
    {
        $builder
            ->add('published', 'boolean')
            ->add('name')
            ->add('rate')
        ;
    }

    public function buildForm(FormBuilder $builder)
    {
        $builder
            ->add('published', 'checkbox')
            ->add('rate')
        ;
    }

    public function buildTranslationForm(FormBuilder $builder)
    {
        $builder
            ->add('name')
        ;
    }
}
