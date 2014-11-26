<?php

namespace Kek\ShopBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints;

class CheckoutAddressType extends AbstractType
{
    protected $translator;
    protected $request;
    protected $user;
    protected $addressTypes;

    public function __construct($translator, $request, $user, $addressTypes)
    {
        $this->request = $request;
        $this->user = $user;
        $this->addressTypes = $addressTypes;
        $this->translator = $translator;
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
            if (intval($this->request->request->get('kek_shop_checkout_address')[$label->getId().'AddressChoice']) === 0) {
                $constraints = [new Constraints\NotBlank];
            }

            $builder
                ->add($label->getId().'FirstName', 'text', [
                    'constraints' => $constraints,
                    'label' => 'FirstName',
                ])
                ->add($label->getId().'LastName', 'text', [
                    'constraints' => $constraints,
                    'label' => 'LastName',
                ])
                ->add($label->getId().'Phone', 'text', [
                    'constraints' => $constraints,
                    'label' => 'Phone',
                ])
                ->add($label->getId().'Address', 'text', [
                    'constraints' => $constraints,
                    'label' => 'Address',
                ])
                ->add($label->getId().'City', 'text', [
                    'constraints' => $constraints,
                    'label' => 'City',
                ])
                ->add($label->getId().'Province', 'text', [
                    'constraints' => $constraints,
                    'label' => 'Province',
                    'data' => 'Québec',
                ])
                ->add($label->getId().'Country', 'text', [
                    'constraints' => $constraints,
                    'label' => 'Country',
                    'data' => 'Canada',
                ])
                ->add($label->getId().'Zip', 'text', [
                    'constraints' => $constraints,
                    'label' => 'Zip',
                ])
            ;

            // if ($this->user) {
            //     $builder->add('save'.ucfirst($label->getId()).'AddressToAddressBook', 'checkbox');
            // }
        }
    }

    public function getName()
    {
        return 'kek_shop_checkout_address';
    }

    protected function buildAddressChoiceField($builder, array $types)
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

        // $i = 0;
        // foreach ($types as $type) {
        //     if ($i !== 0) {
        //         $choices[-1*$type->getId()] = $this->translator->trans('use_address', ['%type%' => $type]);
        //     }
        //     $i++;
        // }

        // set default data
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

        $builder->add($types[0]->getId().'AddressChoice', 'choice', [
            'choices' => $choices,
            'data' => $data,
            'attr' => [
                'class' => 'address-choice form-control',
                'data-type' => $types[0],
            ],
        ]);
    }
}
