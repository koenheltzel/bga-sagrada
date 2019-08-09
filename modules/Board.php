<?php

namespace Sag;

use Sagrada;

class Board {

    public const WIDTH = 5;
    public const HEIGHT = 4;

    /**
     * @var Pattern
     */
    public $pattern;
    public $spaces;

    public function __construct($playerId) {
        $this->pattern = PlayerDatas::get()->getPlayerData($playerId)->patterns[0];

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

}