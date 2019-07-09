<?php

namespace Sagrada;

class Colors {

    /**
     * @var Color[]
     */
    public $colors;

    static private $instance = null;

    /**
     * @return Colors
     */
    public static function get() {
        if (is_null(static::$instance)) {
            static::$instance = new Colors();
        }
        return static::$instance;
    }

    public function __construct() {
        $this->colors = [
            'R' => new Color('R', 'Red'),
            'G' => new Color('G', 'Green'),
            'B' => new Color('B', 'Blue'),
            'Y' => new Color('Y', 'Yellow'),
            'P' => new Color('P', 'Purple'),
        ];
    }

    /**
     * @param $char
     * @return Color
     */
    public function getColor($char) {
        return $this->colors[$char];
    }

}
