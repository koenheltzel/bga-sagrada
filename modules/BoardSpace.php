<?php

namespace Sagrada;

class BoardSpace {

    public $x;
    public $y;
    /**
     * @var Board
     */
    public $board;

    public function __construct(Board $board, $x, $y) {
        $this->x = $x;
        $this->y = $y;
        $this->board = $board;
    }

    public function getPatternColor() {
        return $this->board->pattern->getColor($this->x, $this->y);
    }

    public function getPatternValue() {
        return $this->board->pattern->getValue($this->x, $this->y);
    }

}