<?php

namespace Sagrada;

class Pattern {

    public $name;
    public $difficulty;
    public $pattern;

    public function __construct($name, $difficulty, $pattern) {
        $this->name = $name;
        $this->difficulty = $difficulty;
        $this->pattern = $pattern;
    }

    /**
     * @param int $x
     * @param int $y
     * @return string|bool
     */
    public function getColor(int $x, int $y) {
        $char = $this->pattern[$y][$x];
        if (strstr("RBGYP", $char)) {
            return $char;
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
        $char = $this->pattern[$y][$x];
        if (strstr("123456", $char)) {
            return (int)$char;
        }
        else {
            return false;
        }
    }

}