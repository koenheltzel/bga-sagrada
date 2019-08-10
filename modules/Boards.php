<?php

namespace Sag;

use Sagrada;

class Boards {

    /**
     * @var Board[]
     */
    public $boards = null;

    static private $instance = null;

    /**
     * @return Boards
     */
    public static function get() {
        if (is_null(self::$instance)) {
            self::$instance = new Boards();
        }
        return self::$instance;
    }

    public function __construct() {
        $this->load();
    }

    /**
     * @param $char
     * @return Color
     */
    public function load() {
        $this->boards = [];
        foreach (Sagrada::get()->loadPlayersBasicInfos() as $playerId => $player){
            $board = new Board($playerId);
            $this->boards[$playerId] = $board;
        }
    }

}
