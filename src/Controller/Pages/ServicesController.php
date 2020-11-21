<?php

namespace App\Controller\Pages;

use App\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServicesController extends AbstractController {

    /**
     * @Route("/evenements", name="page_events")
     * @return Response
     */
    public function events(): Response {
        return $this->render('pages/events.html.twig');
    }

    /**
     * @Route("/reservation", name="page_booking")
     * @return Response
     */
    public function booking(): Response {
        return $this->render('pages/booking.html.twig');
    }

}
