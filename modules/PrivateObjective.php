<?php

namespace Sag;

class PrivateObjective {

    /**
     * @var Color
     */
    public $color;

    /**
     * PrivateObjective constructor.
     * @param $color Color
     */
    public function __construct($color) {
        $this->color = $color;
        $this->description = "Sum of values on {$color->name} dice";
    }

}