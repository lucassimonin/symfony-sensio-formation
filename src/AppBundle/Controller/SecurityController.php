<?php

namespace AppBundle\Controller;

use AppBundle\Form\UserType;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("{_locale}/game", requirements={ "_locale" = "fr|en" })
 */
class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction()
    {
        $helper = $this->get('security.authentication_utils');

        return $this->render('security/login.html.twig', [
            'last_username' => $helper->getLastUsername(),
            'error' => $helper->getLastAuthenticationError(),
        ]);
    }

    /**
     * @Route("/login-check", name="login_check")
     */
    public function loginCheckAction()
    {
        // Code never executed as the firewall intercept the request before the
        // Routing component can even match the pattern with the action.
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {
        // Code never executed as the firewall intercept the request before the
        // Routing component can even match the pattern with the action.
    }

    /**
     * @Route("/register", name="register")
     */
    public function registerAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->get('app.manager.user')->save($user);

            $this->addFlash('notice', 'You have been successfully added to the big family of the hangman game!');

            return $this->redirectToRoute('game_home');
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/users", name="users")
     */
    public function listUsersAction()
    {
        return $this->render('security/list_users.html.twig', [
            'users' => $this->getDoctrine()->getRepository('AppBundle:User')->findAll(),
        ]);
    }
}
