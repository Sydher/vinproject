<?php

namespace App\Service\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use Twig\Environment;

class AccessDeniedHandler implements AccessDeniedHandlerInterface {

    /** @var UrlGeneratorInterface */
    private $urlGenerator;

    /** @var Environment */
    private $twig;

    /**
     * AccessDeniedHandler constructor.
     * @param UrlGeneratorInterface $urlGenerator
     * @param Environment $twig
     */
    public function __construct(UrlGeneratorInterface $urlGenerator, Environment $twig) {
        $this->urlGenerator = $urlGenerator;
        $this->twig = $twig;
    }

    public function handle(Request $request, AccessDeniedException $accessDeniedException) {
        return new Response(
            $this->twig->render('error403.html.twig'));
    }

}