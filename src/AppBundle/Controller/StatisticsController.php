<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 12/1/16
 * Time: 6:07 PM
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class StatisticsController
 * @package AppBundle\Controller
 *
 * @Route("/stats")
 */
class StatisticsController extends Controller
{
    /**
     * @return array
     *
     * @Route("/", name="stats_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager()->getRepository('AppBundle:Round');
        $rounds = $em->findAll();

        return array(
          'rounds' => $rounds
        );
    }
}