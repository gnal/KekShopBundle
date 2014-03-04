<?php

namespace Kek\ShopBundle\Admin;

use Msi\AdminBundle\Admin\Admin;
use Msi\AdminBundle\Grid\GridBuilder;
use Symfony\Component\Form\FormBuilder;

class AddressAdmin extends Admin
{
    public function configure()
    {
        // $this->options = [
        //     'search_fields' => ['a.id', 'translations.name'],
        //     'form_template' => 'KekShopBundle:Tax/Admin:form.html.twig',
        // ];

        $this->setParent($this->container->get('msi_user_user_admin'));
    }

    public function buildGrid(GridBuilder $builder)
    {
        $builder
            ->add('firstName')
        ;
    }

    public function buildForm(FormBuilder $builder)
    {
        $builder
            // ->add('published', 'checkbox')
        ;
    }

    public function buildTranslationForm(FormBuilder $builder)
    {
        $builder
            // ->add('name')
        ;
    }
}
