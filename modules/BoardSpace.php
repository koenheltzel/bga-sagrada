<?php

namespace Sag;

class BoardSpace {

    public $x;
    public $y;

    /**
     * @var Die_
     */
    public $die;

    /**
     * @var Color
     */
    public $patternColor;

    /**
     * @var int
     */
    public $patternValue;

    public function __construct($x, $y, Board $board) {
        $this->x = $x;
        $this->y = $y;
        $this->patternColor = $board->pattern ? $board->pattern->getColor($this->x, $this->y) : null;
        $this->patternValue = $board->pattern ? $board->pattern->getValue($this->x, $this->y) : null;
    }

}