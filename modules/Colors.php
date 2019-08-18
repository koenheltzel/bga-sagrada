<?php

namespace Sag;

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
        if (is_null(self::$instance)) {
            self::$instance = new Colors();
        }
        return self::$instance;
    }

    public function __construct() {
        $this->colors = [
            'R' => new Color('R', 'Red', Color::RED),
            'G' => new Color('G', 'Green', Color::GREEN),
            'B' => new Color('B', 'Blue', Color::BLUE),
            'Y' => new Color('Y', 'Yellow', Color::YELLOW),
            'P' => new Color('P', 'Purple', Color::PURPLE),
        ];
    }

    /**
     * @param $char
     * @return Color
     */
    public function getColor($char) {
        return $this->colors[$char];
    }

    public static function getColorByHex($hex) {
        switch ($hex) {
            case Color::RED:
                return Colors::get()->getColor('R');
                break;
            case Color::GREEN:
                return Colors::get()->getColor('G');
                break;
            case Color::BLUE:
                return Colors::get()->getColor('B');
                break;
            case Color::PURPLE:
                return Colors::get()->getColor('P');
                break;
        }
    }

}
