<?php

namespace Sag;

class Die_ {

    /**
     * @var Color
     */
    public $color;

    /**
     * @var int
     */
    public $value;

    public function __construct($color, $value) {
        $this->color = $color;
        $this->value = $value;
    }

}