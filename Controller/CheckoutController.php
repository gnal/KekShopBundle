<?php

namespace Kek\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Validator\Constraints;
use Kek\ShopBundle\Form\Type\CheckoutAddressType;

class CheckoutController extends Controller
{
    /**
     * @Template()
     */
    public function addressAction()
    {
        $order = $this->get('kek_shop.order_provider')->getCurrentOrder();

        // if the user already saved addresses for order, then skip this step
        // user has to be able to edit address tho
        // if ($order->getAddresses()->count()) {
        //     return $this->redirect($this->generateUrl('kek_shop_checkout_review'));
        // }

        $addressTypes = $this->container->get('doctrine')->getRepository('KekShopBundle:AddressType')->findBy([
            'published' => true,
        ]);

        $form = $this->createForm(new CheckoutAddressType($this->getRequest(), $this->getUser(), $addressTypes));

        if ($this->getRequest()->isMethod('POST')) {
            $form->bind($this->getRequest());
            if ($form->isValid()) {
                // die(var_dump($form->getData()));
                $data = $form->getData();

                // delete all previously saved addresses
                foreach ($order->getAddresses() as $value) {
                    $this->getDoctrine()->getEntityManager()->remove($value);
                }

                foreach ($addressTypes as $type) {
                    // create a new orderaddress object
                    $orderAddressClass = $this->container->getParameter('kek_shop.order_address.class');
                    $orderAddress = new $orderAddressClass;

                    $addressId = $data[$type.'AddressChoice'];

                    // we check if address is from address book or not
                    if (!$addressId) {
                        $orderAddress->getTypes()->add($type);
                        $orderAddress
                            ->setOrder($order)
                            ->setFirstName($data[$type.'FirstName'])
                            ->setLastName($data[$type.'LastName'])
                            ->setPhone($data[$type.'Phone'])
                            ->setAddress($data[$type.'Address'])
                            ->setCity($data[$type.'City'])
                            ->setProvince($data[$type.'Province'])
                            ->setZip($data[$type.'Zip'])
                            ->setCountry($data[$type.'Country'])
                        ;
                    } else {
                        $address = $this->getDoctrine()->getRepository($this->container->getParameter('kek_shop.address.class'))->find($addressId);

                        $orderAddress->getTypes()->add($type);
                        $orderAddress
                            ->setOrder($order)
                            ->setFirstName($address->getFirstName())
                            ->setLastName($address->getLastName())
                            ->setPhone($address->getPhone())
                            ->setAddress($address->getAddress())
                            ->setCity($address->getCity())
                            ->setProvince($address->getProvince())
                            ->setZip($address->getZip())
                            ->setCountry($address->getCountry())
                        ;
                    }

                    $this->getDoctrine()->getEntityManager()->persist($orderAddress);
                }

                $this->getDoctrine()->getEntityManager()->flush();

                return $this->redirect($this->generateUrl('kek_shop_checkout_review'));
            }
        }

        return [
            'form' => $form->createView(),
            'address_types' => $addressTypes,
        ];
    }

    /**
     * @Template()
     */
    public function reviewAction()
    {
        $repository = $this->getDoctrine()->getRepository('KekShopBundle:Tax');
        $taxes = $repository->findBy([
            'published' => true,
        ]);

        return [
            'order' => $order = $this->get('kek_shop.order_provider')->getCurrentOrder(),
            'calculator' => $this->get('kek_shop.calculator'),
            'taxes' => $taxes,
        ];
    }
}
