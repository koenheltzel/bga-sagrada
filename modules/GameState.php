<?php

namespace Sag;

use Sagrada;

class GameState {

    /**
     * @var int
     */
    public $diceBagR;

    /**
     * @var int
     */
    public $diceBagG;

    /**
     * @var int
     */
    public $diceBagB;

    /**
     * @var int
     */
    public $diceBagY;

    /**
     * @var int
     */
    public $diceBagP;

    /**
     * @var Array
     */
    public $publicObjectiveIds;

    /**
     * @var GameState
     */
    static private $instance = null;

    /**
     * @return GameState
     */
    static public function get(){
        if (is_null(self::$instance)) {
            self::$instance = new GameState();
        }
        return self::$instance;
    }

    public function __construct() {
        $this->load();
    }

    public function init($players) {
        $sql = "INSERT INTO sag_game_state VALUES ()";
        Sagrada::db($sql);

        $this->diceBagR = 18;
        $this->diceBagG = 18;
        $this->diceBagB = 18;
        $this->diceBagY = 18;
        $this->diceBagP = 18;

        $publicObjectivesCount = count($players) > 1 ? 3 : 2; // Normally 3 public objectives are assigned, but in a solo game 2.
        $publicObjectives = Sagrada::db("SELECT * FROM sag_publicobjectives ORDER BY RAND() LIMIT {$publicObjectivesCount}")->fetch_all(MYSQLI_ASSOC);
        $this->publicObjectiveIds = array_map(function($publicObjective) { return $publicObjective['id'] ;}, $publicObjectives);

        $this->save();
    }

    public function load() {
        $result = Sagrada::db("SELECT * FROM sag_game_state")->fetch_all(MYSQLI_ASSOC);
        if (count($result) > 0) {
            $result = array_shift($result);
            foreach (Colors::get()->colors as $char => $color) {
                $this->{"diceBag$char"} = $result['dice_bag_' . strtolower($char)];
            }
            $this->publicObjectiveIds = explode(',', $result['public_objectives']);
        }
    }

    public function save() {
        $publicObjectivesIdsString = implode(',',$this->publicObjectiveIds);
        $sql = "            
            UPDATE sag_game_state
            SET dice_bag_r        = {$this->diceBagR},
                dice_bag_g        = {$this->diceBagG},
                dice_bag_b        = {$this->diceBagB},
                dice_bag_y        = {$this->diceBagY},
                dice_bag_p        = {$this->diceBagP},
                public_objectives = '{$publicObjectivesIdsString}'
        ";
        Sagrada::db($sql);
    }

    public function diceBagTotal() {
        $total = 0;
        foreach (Colors::get()->colors as $char => $color) {
            $total += $this->{"diceBag$char"};
        }
        return $total;
    }

}