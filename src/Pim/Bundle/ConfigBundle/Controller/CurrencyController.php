<?php

namespace Pim\Bundle\ConfigBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Currency controller for configuration
 *
 * @author    Romain Monceau <romain@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *
 * @Route("/currency")
 */
class CurrencyController extends Controller
{

    /**
     * List currencies
     *
     * @return multitype
     *
     * @Route("/index")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }
}
