<?php

namespace Kek\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\Validator\Constraints;

class AddressController extends Controller
{
    /**
     * @Template()
     */
    public function indexAction()
    {
        return [];
    }

    /**
     * @Template()
     */
    public function newAction()
    {
        $class = $this->container->getParameter('kek_shop.address.class');
        $object = new $class;

        $form = $this->getForm($object);

        if ($this->getRequest()->isMethod('POST')) {
            $form->bind($this->getRequest());

            if ($form->isValid()) {
                $this->getUser()->getAddresses()->add($object);
                $this->getDoctrine()->getManager()->flush();

                $response = $this->redirect($this->generateUrl('kek_shop_address_index'));

                return $response;
            }
        }

        return [
            'form' => $form = $form->createView(),
        ];
    }

    /**
     * @Template()
     */
    public function editAction()
    {
        $class = $this->container->getParameter('kek_shop.address.class');
        $repository = $this->getDoctrine()->getRepository($class);
        $object = $repository->findOneBy([
            'id' => $this->getRequest()->attributes->get('address'),
        ]);

        if (!$object || !$this->getUser()->getAddresses()->contains($object)) {
            throw $this->createNotFoundException();
        }

        $form = $this->getForm($object);

        if ($this->getRequest()->isMethod('POST')) {
            $form->bind($this->getRequest());

            if ($form->isValid()) {
                $this->getDoctrine()->getManager()->flush();

                $response = $this->redirect($this->generateUrl('kek_shop_address_index'));

                return $response;
            }
        }

        return [
            'form' => $form = $form->createView(),
        ];
    }

    public function deleteAction()
    {
        $class = $this->container->getParameter('kek_shop.address.class');
        $repository = $this->getDoctrine()->getRepository($class);
        $object = $repository->findOneBy([
            'id' => $this->getRequest()->attributes->get('address'),
        ]);

        if (!$object || !$this->getUser()->getAddresses()->contains($object)) {
            throw $this->createNotFoundException();
        }

        $this->getUser()->getAddresses()->removeElement($object);
        $this->getDoctrine()->getManager()->flush();

        $response = $this->redirect($this->generateUrl('kek_shop_address_index'));

        return $response;
    }

    private function getForm($object)
    {
        $builder = $this->createFormBuilder($object);
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('phone')
            ->add('address')
            ->add('city')
            ->add('province')
            ->add('zip')
            ->add('country')
        ;

        if (!$this->getUser()->getBillingAddress() || $object->getIsDefaultBilling()) {
            $builder->add('isDefaultBilling');
        }

        if (!$this->getUser()->getShippingAddress() || $object->getIsDefaultShipping()) {
            $builder->add('isDefaultShipping');
        }

        return $builder->getForm();
    }
}
