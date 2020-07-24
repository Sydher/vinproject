<?php

namespace App\Controller;

use Symfony\Component\Form\FormInterface;

/**
 * Class AbstractController
 * @package App\Controller
 * @method App\Entity\User|null getUser()
 */
class AbstractController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController {

    /**
     * Affiche la liste de erreurs sous forme de message flash.
     * TODO implémentation flash :
     *      https://github.com/Grafikart/Grafikart.fr/blob/master/templates/partials/flash.html.twig
     * @param FormInterface $form
     */
    protected function flashErrors(FormInterface $form): void {
        /** @var FormError[] $errors */
        $errors = $form->getErrors();
        $messages = [];
        foreach ($errors as $error) {
            $messages[] = $error->getMessage();
        }
        $this->addFlash('error', implode("\n", $messages));
    }

}
