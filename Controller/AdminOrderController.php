<?php

namespace Kek\ShopBundle\Controller;

use Msi\AdminBundle\Controller\CoreController as Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\Validator\Constraints;

class AdminOrderController extends Controller
{
    /**
     * @Template()
     */
    public function showAction()
    {
        return [
            'order' => $this->admin->getObject(),
            'calculator' => $this->get('kek_shop.calculator'),
        ];
    }
}
