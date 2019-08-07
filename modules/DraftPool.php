<?php

namespace Sag;

use Sagrada;

class DraftPool {

    /**
     * @var Die_[]
     */
    public $dice = [];

    static private $instance = null;

    /**
     * @return DraftPool
     */
    public static function get() {
        if (is_null(self::$instance)) {
            self::$instance = new DraftPool();
        }
        return self::$instance;
    }

    public function __construct() {
        $this->load();
    }

    public function fill($goalSize) {
        while (count($this->dice) < $goalSize) {
            $diceColors = [];
            foreach (Colors::get()->colors as $char => $color) {
                $colorTotal = GameState::get()->{"diceBag$char"};
                $colorArray = array_fill(0, $colorTotal, $char);
                $diceColors = array_merge($diceColors, $colorArray);
            }
            $randomColorIndex = bga_rand(0, count($diceColors) - 1);
            $color = Colors::get()->getColor($diceColors[$randomColorIndex]);
            $value = bga_rand(1, 6);
            $this->dice[] = new Die_($color, $value);

            Sagrada::db("UPDATE sag_game_state SET dice_bag_{$color->char} = dice_bag_{$color->char} - 1");
        }
        $this->save();
    }

    public function load() {
        $results = Sagrada::db("SELECT * FROM sag_draftpool ORDER BY id")->fetch_all(MYSQLI_ASSOC);
        if (count($results) > 0) {
            foreach ($results as $result) {
                $die = new Die_(Colors::get()->getColor($result['die_color']), $result['die_value']);
                $die->draftPoolId = $result['id'];
                $this->dice[] = $die;
            }
        }
    }

    public function save() {
        Sagrada::db('TRUNCATE TABLE sag_draftpool');

        $sql = "
            INSERT INTO sag_draftpool (die_color, die_value)
            VALUES
        ";
        $sqlValues = [];
        foreach ($this->dice as $die) {
            $sqlValues[] = "('{$die->color->char}', {$die->value})";
        }
        $sql .= implode(',', $sqlValues);
        Sagrada::db($sql);
    }

}