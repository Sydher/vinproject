<?php

namespace App\Controller\Pages;

use App\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LegalController extends AbstractController {

    /**
     * @Route("/credits", name="credits")
     * @return Response
     */
    public function credits(): Response {
        return $this->render('credits.html.twig');
    }

}
