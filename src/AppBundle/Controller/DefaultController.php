<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Round;
use AppBundle\Game\ComputerPlayer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * indexAction()
     * Return a response to render for the homepage
     *
     * @Route("/", name="homepage")
     * @Method("GET")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        // Generate a CSRF Token
        $token = $this->get('form.csrf_provider')->generateCsrfToken('game_token');

        return array(
          '_token' => $token
        );
    }

    /**
     * @param Request $request
     *
     * @Route("/", name="turn")
     * @Method("POST")
     * @Template()
     *
     * @return array|RedirectResponse
     */
    public function turnAction(Request $request)
    {
        $round = new Round();
        $computer = new ComputerPlayer();
        $em = $this->getDoctrine()->getManager();
        $validation = $this->get('validator');

        // Get and validate the CSRF Token
        $token = $request->request->get('_token');

        if (!$this->get('form.csrf_provider')->isCsrfTokenValid('game_token', $token)) {
            $this->addFlash('danger', 'An error occurred, please play again.');

            return $this->redirectToRoute('homepage');
        }

        // Get the player's choice from the request body
        $playerChoice = $request->request->get('choice', 0);
        // Set the player's choice
        $round->setPlayerChoice($playerChoice);

        // Get the computer's choice
        $computerChoice = $computer->takeTurn();
        // Set the computer's choice
        $round->setComputerChoice($computerChoice);

        // Validate the $round
        $errors = $validation->validate($round);

        if (count($errors) > 0) {
            $this->addFlash('danger', (string) $errors);

            return $this->redirectToRoute('homepage');
        }

        $em->persist($round);
        $em->flush();

        return array(
          'round' => $round
        );
    }
}
