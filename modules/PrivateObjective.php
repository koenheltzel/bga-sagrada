<?php

namespace Sag;

class PrivateObjective {

    /**
     * @var Color
     */
    public $color;

    public function __construct($color) {
        $this->color = $color;
    }

}