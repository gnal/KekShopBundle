<?php

namespace Kek\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ProductController extends Controller
{
    /**
     * @Route("/product")
     * @Template()
     */
    public function indexAction()
    {
        $class = $this->container->getParameter('kek_shop.product.class');
        $repository = $this->getDoctrine()->getRepository($class);

        $parameters['products'] = $repository->findAll();

        return $parameters;
    }
}
