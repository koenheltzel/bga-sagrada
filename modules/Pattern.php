<?php

namespace Sag;

class Pattern {

    public $id;
    public $name;
    public $difficulty;
    public $pattern;
    public $pair;

    /**
     * @param int $x
     * @param int $y
     * @return Color|bool
     */
    public function getColor(int $x, int $y) {
        $char = substr($this->pattern, $y * 5 + $x, 1);
        if (strstr("RBGYP", $char)) {
            return Colors::get()->getColor($char);
        }
        else {
            return false;
        }
    }

    /**
     * @param int $x
     * @param int $y
     * @return int|bool
     */
    public function getValue(int $x, int $y) {
        $char = substr($this->pattern, $y * 5 + $x, 1);
        if (strstr("123456", $char)) {
            return (int)$char;
        }
        else {
            return false;
        }
    }

}