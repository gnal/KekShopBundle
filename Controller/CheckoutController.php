<?php

namespace Kek\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Validator\Constraints;
use Kek\ShopBundle\Form\Type\CheckoutAddressType;
use Kek\ShopBundle\Event\FormEvent;

class CheckoutController extends Controller
{
    /**
     * @Template()
     */
    public function addressAction()
    {
        $order = $this->get('kek_shop.order_provider')->getCurrentOrder();

        if (!$order) {
            return $this->redirect($this->generateUrl('kek_shop_product_index'));
        }

        // if the user already saved addresses for order, then skip this step
        // user has to be able to edit address tho
        // if ($order->getAddresses()->count()) {
        //     return $this->redirect($this->generateUrl('kek_shop_checkout_review'));
        // }

        $addressTypes = $this->container->get('doctrine')->getRepository('KekShopBundle:AddressType')->findBy([
            'published' => true,
        ]);

        $formTypeClass = $this->container->getParameter('kek_shop.checkout_address_form_type_class.class');
        $form = $this->createForm(new $formTypeClass($this->get('translator'), $this->getRequest(), $this->getUser(), $addressTypes));

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
                        // in case we allow submission of empty address type fields, we must skip the address type
                        // for example, if you disable validation on shipping, you cant save it
                        if (!$data[$type.'LastName']) {
                            continue;
                        }

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

                $event = new FormEvent($form, $this->getDoctrine()->getEntityManager());
                $this->container->get('event_dispatcher')->dispatch(FormEvent::CHECKOUT_ADDRESS_SUCCESS, $event);

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
