<?php

namespace Kek\ShopBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Form\FormInterface;
use Doctrine\Common\Persistence\ObjectManager;

class FormEvent extends Event
{
    const CHECKOUT_ADDRESS_SUCCESS = 'kek_shop.checkout_address.success';

    private $om;
    private $form;

    public function __construct(FormInterface $form, ObjectManager $om)
    {
        $this->form = $form;
        $this->om = $om;
    }

    public function getForm()
    {
        return $this->form;
    }

    public function getManager()
    {
        return $this->om;
    }
}
