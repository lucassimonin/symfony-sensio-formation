<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route(
 *     "{_locale}/game",
 *     requirements={ "_locale" = "fr|en" }
 * )
 */
class GameController extends Controller
{
    /**
     * @Cache(smaxage=10)
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function hourAction()
    {
        return new Response(date('H:i:s'));
    }
    /**
     * @Route("/", name="game_home")
     * @Security("is_granted('PLAY')")
     */
    public function homeAction()
    {
        return $this->render('game/home.html.twig', [
            'game' => $this->get('game_runner')->loadGame($this->getParameter('word_length')),
        ]);
    }

    /**
     * @Route("/won", name="game_won")
     */
    public function wonAction()
    {
        return $this->render('game/won.html.twig', [
            'game' => $this->get('game_runner')->resetGameOnSuccess(),
        ]);
    }

    /**
     * @Route("/failed", name="game_failed")
     */
    public function failedAction()
    {
        return $this->render('game/failed.html.twig', [
            'game' => $this->get('game_runner')->resetGameOnFailure(),
        ]);
    }

    /**
     * @Route("/reset", name="game_reset")
     */
    public function resetAction()
    {
        $this->get('game_runner')->resetGame();

        return $this->redirectToRoute('game_home');
    }

    /**
     * This action plays a letter.
     *
     * @Route("/play/{letter}", name="game_play_letter", requirements={
     *   "letter"="[A-Z]"
     * })
     * @Method("GET")
     */
    public function playLetterAction($letter)
    {
        $game = $this->get('game_runner')->playLetter($letter);

        if (!$game->isOver()) {
            return $this->redirectToRoute('game_home');
        }

        return $this->redirectToRoute($game->isWon() ? 'game_won' : 'game_failed');
    }

    /**
     * This action plays a word.
     *
     * @Route("/play", name="game_play_word", condition="request.request.has('word')")
     * @Method("POST")
     */
    public function playWordAction(Request $request)
    {
        $game = $this->get('game_runner')->playWord($request->request->get('word'));

        return $this->redirectToRoute($game->isWon() ? 'game_won' : 'game_failed');
    }

    public function testimonialsAction()
    {
        return $this->render('game/testimonials.html.twig', [
            'testimonials' => [
                'John Doe' => 'I love this game, so addictive!',
                'Martin Durand' => 'Best web application ever',
                'Paul Smith' => 'Awesomeness!',
            ],
        ]);
    }
}
