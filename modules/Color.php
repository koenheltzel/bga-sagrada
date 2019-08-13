<?php

namespace Sag;

class Color {

    const RED = 'e72525';
    const GREEN = '2c9d47';
    const BLUE = '09b4e3';
    const PURPLE = '922f8e';

    public $char;
    public $name;

    public function __construct($char, $name) {
        $this->char = $char;
        $this->name = $name;
    }

}
