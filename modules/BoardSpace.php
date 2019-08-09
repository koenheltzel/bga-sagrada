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
        $this->patternColor = $board->pattern->getColor($this->x, $this->y);
        $this->patternValue = $board->pattern->getValue($this->x, $this->y);
    }

}