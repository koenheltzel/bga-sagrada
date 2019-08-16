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
        if ($this->totalDice() == 0) {
            for($y = 0; $y < self::HEIGHT; $y++) {
                for($x = 0; $x < self::WIDTH; $x++) {
                    if ($x == 0 || $y == 0 || $x == (self::WIDTH - 1) || $y == (self::HEIGHT - 1)) {
                        $positions[] = [$x, $y];
                    }
                }
            }
        }
        else {
            for($y = 0; $y < self::HEIGHT; $y++) {
                for ($x = 0; $x < self::WIDTH; $x++) {
                    // Check if already occupied by die.
                    if ($this->getDie($x, $y)) {
                        continue;
                    }
                    // Check pattern color
                    $patternColor = $this->pattern->getColor($x, $y);
                    if ($patternColor && $patternColor <> $die->color) {
                        continue;
                    }
                    // Check pattern value
                    $patternValue = $this->pattern->getValue($x, $y);
                    if (is_numeric($patternValue) && $patternValue <> $die->value) {
                        continue;
                    }
                    // All restrictions passed, add position as legal
                    $positions[] = [$x, $y];
                }
            }
        }
        return $positions;
    }

}