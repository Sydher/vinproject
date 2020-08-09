<?php

namespace App\Service;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigExtension extends AbstractExtension {

    public function getFunctions(): array {
        return [
            new TwigFunction('menu_active', [$this, 'menuActive'], ['is_safe' => ['html'], 'needs_context' => true]),
        ];
    }

    /**
     * Ajout une class active pour les éléments actifs du menu.
     * @param array<string,mixed> $context
     * @param string $name
     * @return string
     */
    public function menuActive(array $context, string $name): string {
        if (($context['menu'] ?? null) === $name) {
            return 'active"';
        }
        return '';
    }

}