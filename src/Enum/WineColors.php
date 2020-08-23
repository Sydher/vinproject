<?php

namespace App\Enum;

/**
 * Liste des couleurs de Vins.
 * @package App\Enum
 */
class WineColors {

    public const ROUGE = "Rouge";
    public const BLANC = "Blanc";
    public const ROSE = "Rosé";

    /**
     * Retourne la liste des couleurs de Vins.
     * @return string[]
     */
    public static function getColors(): array {
        return [self::ROUGE, self::BLANC, self::ROSE];
    }

    /**
     * Retourne la liste des couleurs de Vins pour un formulaire.
     * @return array
     */
    public static function getColorsChoice(): array {
        return [
            self::ROUGE => self::ROUGE,
            self::BLANC => self::BLANC,
            self::ROSE => self::ROSE,
        ];
    }

}
