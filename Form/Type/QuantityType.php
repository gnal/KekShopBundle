<?php

namespace Kek\ShopBundle\Form\Type;

use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints;
use Kek\ShopBundle\Model\OrderItem;

class QuantityType extends AbstractType
{
    protected $item;

    public function __construct(OrderItem $item)
    {
        $this->item = $item;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('quantity', 'text', [
            'attr' => [
                'class' => 'form-control input-sm',
            ],
            'constraints' => [new Constraints\Range([
                'min' => 1,
                'max' => 999,
            ])],
        ]);
    }

    public function getName()
    {
        return 'kek_shop_quantity';
    }
}
