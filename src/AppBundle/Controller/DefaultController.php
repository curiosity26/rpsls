<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Round;
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
        return array();
    }

    /**
     * @param Request $request
     *
     * @Route("/", name="turn")
     * @Method("POST")
     *
     * @return RedirectResponse
     */
    public function turnAction(Request $request)
    {
        $round = new Round();
        $em = $this->getDoctrine()->getManager();

        // Get the player's choice from the request body
        $playerChoice = $request->request->get('choice');
        // Set the player's choice
        $round->setPlayerChoice($playerChoice);

        $em->persist($round);
        $em->flush();

        return $this->redirectToRoute('homepage');
    }
}
