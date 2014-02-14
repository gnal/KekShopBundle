<?php

namespace Kek\ShopBundle\Admin;

use Msi\AdminBundle\Admin\Admin;
use Msi\AdminBundle\Grid\GridBuilder;
use Symfony\Component\Form\FormBuilder;

class ProductAdmin extends Admin
{
    public function configure()
    {
        $this->options = [
            'search_fields' => ['a.id', 'translations.name'],
            'form_template' => 'KekShopBundle:Product/Admin:form.html.twig',
            'order_by' => [
                'translations.name' => 'ASC',
            ],
        ];
    }

    public function buildGrid(GridBuilder $builder)
    {
        $builder
            ->add('published', 'boolean')
            ->add('name')
            ->add('price')
        ;
    }

    public function buildForm(FormBuilder $builder)
    {
        $builder
            ->add('published', 'checkbox')
            ->add('categories')
            ->add('price', 'money', [
                'currency' => false,
            ])
            ->add('taxable')
            ->add('discountedPrice', 'money', [
                'currency' => false,
            ])
            ->add('discountedFrom', 'date', [
                'input' => 'datetime',
                'format' => 'dd-MM-yyyy',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datepicker form-control',
                ],
            ])
            ->add('discountedTo', 'date', [
                'input' => 'datetime',
                'format' => 'dd-MM-yyyy',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datepicker form-control',
                ],
            ])
        ;
    }

    public function buildTranslationForm(FormBuilder $builder)
    {
        $builder
            ->add('name')
            ->add('description', 'textarea', [
                'attr' => [
                    'class' => 'tinymce',
                ],
            ])
        ;
    }
}
