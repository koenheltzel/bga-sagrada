<?php

namespace Sag;

use Sagrada;

class Board {

    public const WIDTH = 5;
    public const HEIGHT = 4;

    public const RESTRICTIONS_VALUE = 1;
    public const RESTRICTIONS_COLOR = 2;

    /**
     * @var Pattern
     */
    public $pattern;
    public $spaces;

    public function __construct($playerId) {
        $playerPatterns = PlayerDatas::get()->getPlayerData($playerId)->patterns;
        $this->pattern = count($playerPatterns) == 1 ? $playerPatterns[0] : null;

        $boardSpaces = [];
        $boardSpacesTmp = Sagrada::DbQuery("SELECT * FROM sag_boardspace WHERE player_id = {$playerId}")->fetch_all(MYSQLI_ASSOC);
        foreach ($boardSpacesTmp as $boardSpaceTmp){
            $boardSpaces["{$boardSpaceTmp['x']}_{$boardSpaceTmp['y']}"] = $boardSpaceTmp;
        }

        $this->spaces = [];
        for ($y = 0; $y < self::HEIGHT; $y++) {
            $spaces = [];
            for ($x = 0; $x < self::WIDTH; $x++) {
                $boardSpace = new BoardSpace($x, $y, $this);
                $index = "{$x}_{$y}";
                if(isset($boardSpaces[$index])) {
                    $boardSpaceData = $boardSpaces[$index];
                    $boardSpace->die = new Die_(Colors::get()->getColor($boardSpaceData['die_color']), $boardSpaceData['die_value']);
                }
                $spaces[] = $boardSpace;
            }
            $this->spaces[] = $spaces;
        }
    }

    public function totalDice() {
        $totalDice = 0;
        foreach ($this->spaces as $row) {
            foreach ($row as $boardSpace) {
                $totalDice += ($boardSpace->die ? 1 : 0);
            }
        }
        return $totalDice;
    }

    public function getDie($x, $y) {
        return $this->spaces[$y][$x]->die ? $this->spaces[$y][$x] : false;
    }

    /**
     * @param $die Die_
     * @return array
     */
    public function getLegalPositions($die, $ignoreRestrictions=null) {
        $positions = [];
        for($y = 0; $y < self::HEIGHT; $y++) {
            for($x = 0; $x < self::WIDTH; $x++) {
                if ($this->totalDice() > 0 || ($x == 0 || $y == 0 || $x == (self::WIDTH - 1) || $y == (self::HEIGHT - 1))) {
                    if ($this->isLegalPosition($x, $y, $die, $ignoreRestrictions)) {
                        // All restrictions passed, add position as legal
                        $positions[] = [$x, $y];
                    }
                }
            }
        }
        return $positions;
    }

    public function isLegalPosition($x, $y, $die, $ignoreRestrictions=null) {
        // Check if already occupied by die.
        if ($this->getDie($x, $y)) {
            return false;
        }
        // Check pattern color
        $patternColor = $this->pattern->getColor($x, $y);
        if ($patternColor && $patternColor != $die->color) {
            return false;
        }
        // Check pattern value
        $patternValue = $this->pattern->getValue($x, $y);
        if (is_numeric($patternValue) && $patternValue != $die->value) {
            return false;
        }

        $boardSpaces = $this->getNeighbouringBoardSpaces($x, $y, true);

        if ($this->totalDice() > 0) {
            $neightbourDieFound = false;
            foreach ($boardSpaces as $boardSpace){
                if ($boardSpace->die) {
                    // There is a populated field adjacent, so this is a legal boardspace if all other restrictions are passed.
                    $neightbourDieFound = true;
                }
            }
            if (!$neightbourDieFound) {
                return false;
            }

            $boardSpaces = $this->getNeighbouringBoardSpaces($x, $y, false);
            foreach ($boardSpaces as $boardSpace){
                if ($boardSpace->die){
                    if ($boardSpace->die->color == $die->color) {
                        return false;
                    }
                    if ($boardSpace->die->value == $die->value) {
                        $positionLegal = false;
                        return false;
                    }
                }
            }
        }

        return true;
    }

    /**
     * @param $x
     * @param $y
     * @param $includeDiagonal
     * @return BoardSpace[]
     */
    public function getNeighbouringBoardSpaces($x, $y, $includeDiagonal) {
        $boardSpaces = [];
        for($xDif = -1; $xDif <= 1; $xDif++) {
            for($yDif = -1; $yDif <= 1; $yDif++) {
                if ($xDif == 0 && $yDif == 0){
                    continue;
                }
                if (!$includeDiagonal && ($xDif != 0 && $yDif != 0)) {
                    continue;
                }
                if ($x + $xDif < 0 || $x + $xDif > self::WIDTH - 1) {
                    continue;
                }
                if ($y + $yDif < 0 || $y + $yDif > self::HEIGHT - 1) {
                    continue;
                }
                $boardSpaces[] = $this->spaces[$y + $yDif][$x + $xDif];
            }
        }
        return $boardSpaces;

        if ($x > 0) $boardSpaces[] = [$x - 1];
    }

}