<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactFormType;
use App\Notification\ContactNotification;
use App\Repository\WineRepository;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;

class HomeController extends AbstractController {

    /**
     * @var WineRepository
     */
    private $wineRepository;

    /**
     * @var CacheInterface
     */
    private $cache;

    public function __construct(WineRepository $wineRepository, CacheInterface $cache) {
        $this->wineRepository = $wineRepository;
        $this->cache = $cache;
    }

    /**
     * @Route("/", name="home")
     * @return Response
     * @throws InvalidArgumentException
     */
    public function index(): Response {
        $newWines = $this->cache->get('last3Created', function() {
            return $this->wineRepository->findLastCreated(3);
        });
        $best1 = $this->cache->get('bestWine1', function() {
            return $this->wineRepository->findByIdJoined(2);
        });
        $best2 = $this->cache->get('bestWine2', function() {
            return $this->wineRepository->findByIdJoined(12);
        });
        $best3 = $this->cache->get('bestWine3', function() {
            return $this->wineRepository->findByIdJoined(6);
        });
        return $this->render('index.html.twig', [
            'controller_name' => 'HomeController',
            'menu' => 'accueil',
            'newWines' => $newWines,
            'best1' => $best1,
            'best2' => $best2,
            'best3' => $best3
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
