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
        $addressTypes = $this->container->get('doctrine')->getRepository('KekShopBundle:AddressType')->findBy([
            'published' => true,
        ]);

        $form = $this->createForm(new CheckoutAddressType($this->getRequest(), $this->getUser(), $addressTypes));

        if ($this->getRequest()->isMethod('POST')) {
            $form->bind($this->getRequest());
            if ($form->isValid()) {
                die('woot woot');
            }
        }

        return [
            'form' => $form->createView(),
            'address_types' => $addressTypes,
        ];
    }
}
