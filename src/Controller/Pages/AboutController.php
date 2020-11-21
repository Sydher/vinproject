<?php

namespace App\Controller\Pages;

use App\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AboutController extends AbstractController {

    /**
     * @Route("/notre-histoire", name="page_history")
     * @return Response
     */
    public function history(): Response {
        return $this->render('pages/history.html.twig');
    }

    /**
     * @Route("/l-equipe", name="page_team")
     * @return Response
     */
    public function team(): Response {
        return $this->render('pages/team.html.twig');
    }

    /**
     * @Route("/notre-boutique", name="page_local_shop")
     * @return Response
     */
    public function localShop(): Response {
        return $this->render('pages/local-shop.html.twig');
    }

    /**
     * @Route("/credits", name="credits")
     * @return Response
     */
    public function credits(): Response {
        return $this->render('pages/credits.html.twig');
    }

}
