<?php

namespace Sagrada\States;

trait StateSelectPattern {
    function argSelectPattern()
    {
        $current_player_id = self::getCurrentPlayerId();
        $sql = "
            SELECT sag_patterns, sag_privateobjectives
            FROM player
            WHERE player_id = {$current_player_id}";
        $playerSagData = self::db($sql)->fetch_assoc();
        $result = [];
        $result['patterns'] = explode(',', $playerSagData['sag_patterns']);
        $result['privateobjectives'] = explode(',', $playerSagData['sag_privateobjectives']);

//        print "<PRE>" . print_r($result, true) . "</PRE>";

        return $result;
    }
}