<?php

namespace App\Controller\Admin;

use App\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController {

    /**
     * @Route("/admin", name="admin_home")
     */
    public function index() {
        return $this->render('admin/index.html.twig', [
            'menu' => 'accueil'
        ]);
    }

}
