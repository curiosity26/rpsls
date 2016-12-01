<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 12/1/16
 * Time: 6:07 PM
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Round;
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
        $choices = array(
          Round::ROCK => 'Rock',
          Round::PAPER => 'Paper',
          Round::SCISSORS => 'Scissors',
          Round::LIZARD => 'Lizard',
          Round::SPOCK => 'Spock',
          );

        $playerWins = $em->totalPlayerWins();
        $computerWins = $em->totalComputerWins();

        $plays = array();

        foreach ($choices as $choice => $name) {
            $plays[$choice]['player'] = $em->totalTimesPlayerPlayed($choice);
            $plays[$choice]['computer'] = $em->totalTimesComputerPlayed($choice);
        }

        return array(
          'playerWins' => $playerWins,
          'computerWins' => $computerWins,
          'choices' => $choices,
          'plays' => $plays
        );
    }
}