<?php

namespace App\Controller\Pages;

use App\Controller\AbstractController;
use App\Entity\Contact;
use App\Form\ContactFormType;
use App\Notification\ContactNotification;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController {

    /**
     * @Route("/faq", name="page_faq")
     * @return Response
     */
    public function faq(): Response {
        return $this->render('pages/faq.html.twig');
    }

    /**
     * @Route("/contact", name="contact")
     * @param Request $request
     * @param ContactNotification $notification
     * @return Response
     */
    public function contact(Request $request, ContactNotification $notification): Response {
        return $this->handleContactForm($request,
            $notification,
            "pages/contact.html.twig",
            "contact");
    }

    /**
     * @Route("/presse", name="page_presse")
     * @return Response
     */
    public function presse(): Response {
        return $this->render('pages/presse.html.twig');
    }

    /**
     * @Route("/proposer-votre-vin", name="page_proposal")
     * @param Request $request
     * @param ContactNotification $notification
     * @return Response
     */
    public function proposal(Request $request, ContactNotification $notification): Response {
        return $this->handleContactForm($request,
            $notification,
            "pages/proposer.html.twig",
            "no_menu");
    }

    /**
     * Gère le formulaire de contact.
     * @param Request $request
     * @param ContactNotification $notification
     * @param string $view la vue à afficher
     * @param string $menu le menu à mettre en valeur
     * @return Response
     */
    private function handleContactForm(Request $request,
                                       ContactNotification $notification,
                                       string $view,
                                       string $menu): Response {
        $contact = new Contact();
        $form = $this->createForm(ContactFormType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $notification->notify($contact);
                $this->flashSuccess("MessageSend");
            } catch (TransportExceptionInterface $e) {
                $this->flashError("Erreur : " . $e->getMessage());
            }
            return $this->redirectToRoute('home');
        }

        return $this->render($view, [
            'form' => $form->createView(),
            'menu' => $menu
        ]);
    }

}
