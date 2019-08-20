<?php

namespace Sag;

use Sagrada;

class PrivateObjectives {

    /**
     * @param Array $colorChars
     * @return PrivateObjective[]
     */
    public static function getPrivateObjectives($colorChars) {
        $privateObjectives = [];
        foreach ($colorChars as $colorChar) {
            $privateObjectives[] = self::getPrivateObjective($colorChar);
        }
        return $privateObjectives;
    }

    /**
     * @param $colorChar
     * @return PrivateObjective
     */
    public static function getPrivateObjective($colorChar) {
        return new PrivateObjective(Colors::get()->getColor($colorChar));
    }

}