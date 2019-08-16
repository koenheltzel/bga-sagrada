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

            Sagrada::DbQuery("UPDATE sag_game_state SET dice_bag_{$color->char} = dice_bag_{$color->char} - 1");
        }
        $this->save();
        $this->load(); // Load again, so ids and legal positions are filled as well.
    }

    public function load() {
        $this->dice = [];
        $results = Sagrada::DbQuery("SELECT * FROM sag_draftpool ORDER BY id")->fetch_all(MYSQLI_ASSOC);
        if (count($results) > 0) {
            foreach ($results as $result) {
                $die = new Die_(Colors::get()->getColor($result['die_color']), $result['die_value']);
                $die->draftPoolId = $result['id'];
                $this->dice[] = $die;
            }
        }
    }

    public function getDiceWithLegalPositions($playerId) {
        foreach($this->dice as &$die) {
            $die->draftLegalPositions = Boards::get()->boards[$playerId]->getLegalPositions($die);
        }
        return unserialize(serialize($this->dice));
    }

    public function save() {
        Sagrada::DbQuery('TRUNCATE TABLE sag_draftpool');

        $sql = "
            INSERT INTO sag_draftpool (die_color, die_value)
            VALUES
        ";
        $sqlValues = [];
        foreach ($this->dice as $die) {
            $sqlValues[] = "('{$die->color->char}', {$die->value})";
        }
        $sql .= implode(',', $sqlValues);
        Sagrada::DbQuery($sql);
    }

    public function deleteDie($id, $color, $value) {
        // Delete the specified die from the draftpool.
        $sql = "
            DELETE FROM sag_draftpool WHERE id = {$id} 
                 AND die_color = '{$color->char}' 
                 AND die_value = {$value}
        ";
        Sagrada::DbQuery($sql);
        if (Sagrada::DbAffectedRow() == 0) {
            throw new BgaUserException('The selected die is not actually in the draft pool.');
        }
        $this->load();
    }

}