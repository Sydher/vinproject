<?php

namespace App\Controller;

use Symfony\Component\Form\FormInterface;
use App\Entity\User;

/**
 * Class AbstractController
 * @package App\Controller
 * @method User|null getUser()
 */
abstract class AbstractController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController {

    /**
     * Affiche la liste de erreurs sous forme de message flash.
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

    /**
     * Créer un message flash d'erreur.
     * @param string $message
     */
    protected function flashError(string $message): void {
        $this->addFlash('error', $message);
    }

    /**
     * Créer un message flash d'attention.
     * @param string $message
     */
    protected function flashWarning(string $message): void {
        $this->addFlash('warning', $message);
    }

    /**
     * Créer un message flash de succès.
     * @param string $message
     */
    protected function flashSuccess(string $message): void {
        $this->addFlash('success', $message);
    }

    /**
     * Créer un message flash d'attention.
     * @param string $message
     */
    protected function flashInfo(string $message): void {
        $this->addFlash('info', $message);
    }

}
