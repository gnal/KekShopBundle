<?php

namespace Kek\ShopBundle\Form\Type;

use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints;

class CheckoutAddressType extends AbstractType
{
    protected $request;
    protected $user;
    protected $addressTypes;

    public function __construct($request, $user, $addressTypes)
    {
        $this->request = $request;
        $this->user = $user;
        $this->addressTypes = $addressTypes;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $tempAddressTypes = $this->addressTypes;
        for ($i=0; $i < count($tempAddressTypes); $i++) {
            if ($i > 0) {
                $last = array_pop($tempAddressTypes);
                array_unshift($tempAddressTypes, $last);
            }
            $this->buildAddressChoiceField($builder, $tempAddressTypes);
        }

        foreach ($this->addressTypes as $label) {
            $constraints = [];
            if (intval($this->request->request->get('kek_shop_checkout_address')[$label.'AddressChoice']) === 0) {
                $constraints = [new Constraints\NotBlank];
            }

            $builder
                ->add($label.'FirstName', 'text', [
                    'constraints' => $constraints,
                ])
                ->add($label.'LastName', 'text', [
                    'constraints' => $constraints,
                ])
                ->add($label.'Phone', 'text', [
                    'constraints' => $constraints,
                ])
                ->add($label.'Address', 'text', [
                    'constraints' => $constraints,
                ])
                ->add($label.'City', 'text', [
                    'constraints' => $constraints,
                ])
                ->add($label.'Province', 'text', [
                    'constraints' => $constraints,
                ])
                ->add($label.'Country', 'text', [
                    'constraints' => $constraints,
                ])
                ->add($label.'Zip', 'text', [
                    'constraints' => $constraints,
                ])
            ;

            if ($this->user) {
                $builder->add('save'.ucfirst($label).'AddressToAddressBook', 'checkbox');
            }
        }
    }

    public function getName()
    {
        return 'kek_shop_checkout_address';
    }

    private function buildAddressChoiceField($builder, array $types)
    {
        $choices = [];
        // try to get user's addresses
        if ($this->user) {
            foreach ($types as $type) {
                if ($address = $this->user->getAddressByType($type)) {
                    $choices[$address->getId()] = $address;
                }
            }

            foreach ($this->user->getAdditionalAddresses() as $address) {
                $choices[$address->getId()] = $address;
            }
        }

        // need a new address choice
        $choices[0] = 'New Address';

        $i = 0;
        foreach ($types as $type) {
            if ($i !== 0) {
                $choices[$type->getId()] = 'Use '.ucfirst($type).' Address';
            }
            $i++;
        }

        // set default data (in other word set the selected option)
        $data = null;
        if ($this->user) {
            foreach ($types as $type) {
                if ($address = $this->user->getAddressByType($type)) {
                    $data = $address->getId();
                    break;
                }
            }

            if (!$data && $this->user->getAdditionalAddresses()->count()) {
                $data = $this->user->getAdditionalAddresses()->first()->getId();
            }

            if (!$data) {
                $data = 0;
            }
        } else {
            $data = 0;
        }

        $builder->add($types[0].'AddressChoice', 'choice', [
            'choices' => $choices,
            'data' => $data,
            'attr' => [
                'class' => 'address-choice form-control',
                'data-type' => $types[0],
            ],
        ]);
    }
}