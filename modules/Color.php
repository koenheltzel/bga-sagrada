<?php

namespace Sag;

class Color {

    const RED = 'e72525';
    const GREEN = '2c9d47';
    const BLUE = '09b4e3';
    const PURPLE = '922f8e';
    const YELLOW = 'e4bd1d';

    public $char;
    public $name;
    public $hexColor;

    public function __construct($char, $name, $hexColor) {
        $this->char = $char;
        $this->name = $name;
        $this->hexColor = $hexColor;
    }

}
