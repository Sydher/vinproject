<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactFormType;
use App\Notification\ContactNotification;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController {

    /**
     * @Route("/", name="home")
     */
    public function index(): Response {
        return $this->render('index.html.twig', [
            'controller_name' => 'HomeController',
            'menu' => 'accueil'
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     * @param Request $request
     * @param ContactNotification $notification
     * @return Response
     */
    public function contact(Request $request, ContactNotification $notification): Response {
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

        return $this->render('contact.html.twig', [
            'form' => $form->createView(),
            'menu' => 'contact'
        ]);
    }

}
