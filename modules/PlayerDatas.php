<?php

namespace Sag;

use Sagrada;

class PlayerDatas {

    /**
     * @var PlayerData[]
     */
    public $datas;

    static private $instance = null;

    /**
     * @return PlayerDatas
     */
    public static function get() {
        if (is_null(self::$instance)) {
            self::$instance = new PlayerDatas();
        }
        return self::$instance;
    }

    public function __construct() {
        $this->load();
    }

    public function load() {
        $this->datas = [];
        $results = Sagrada::DbQuery("SELECT player_id, `sag_patterns`, `sag_private_objectives`, `sag_tokens` FROM `player`")->fetch_all(MYSQLI_ASSOC);
        foreach ($results as $result) {
            $this->datas[$result['player_id']] = new PlayerData($result);
        }
    }

    /**
     * @param int
     * @return PlayerData
     */
    public function getPlayerData($playerId) {
        return $this->datas[$playerId];
    }

}