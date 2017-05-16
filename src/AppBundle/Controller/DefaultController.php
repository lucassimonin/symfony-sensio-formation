<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Contact;
use AppBundle\Form\ContactType;
use AppBundle\Service\ContactService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('index.html.twig');
    }

    /*public function closeAction()
    {
        return $this->render('')
    }*/

    /**
     * @Route("{_locale}/contact", name="contact")
     */
    public function contactAction(Request $request)
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contactService = new ContactService('javier.eguiluz@sensiolabs.com', $this->get('mailer'));
            $contactService->sendMail($contact);

            $this->addFlash('notice', 'Your request has been successfully sent.');

            return $this->redirectToRoute('game_home');
        }

        return $this->render('contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
