<?php

namespace Sag;

class Board {

    public const WIDTH = 5;
    public const HEIGHT = 4;

    /**
     * @var Pattern
     */
    public $pattern;
    public $rows;

    public function __construct(Pattern $pattern) {
        $this->pattern = $pattern;

        $this->rows = [];
        for ($y = 0; $y < self::HEIGHT; $y++) {
            $spaces = [];
            for ($x = 0; $x < self::WIDTH; $x++) {
                $spaces[] = new BoardSpace($this, $x, $y);
            }
            $this->rows[] = $spaces;
        }
    }

}