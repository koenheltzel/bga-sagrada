<?php

namespace Sag;

class DraftPool {

    /**
     * @var Die_[]
     */
    public $dice;

    public function __construct() {

    }

    public function fill() {
        $goal = 5;
        while (count($this->dice) < $goal) {
            break;
        }
    }

}