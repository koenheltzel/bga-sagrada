<?php

namespace Sagrada\States;

use Sagrada\Patterns;

trait StateSelectPatternTrait {
    function argSelectPattern()
    {
        $result = [];
//        $current_player_id = self::getCurrentPlayerId();
//        $sql = "
//            SELECT sag_patterns, sag_privateobjectives
//            FROM player
//            WHERE player_id = {$current_player_id}";
//        $playerSagData = self::db($sql)->fetch_assoc();
//        $result['patterns'] = Patterns::getPatterns(explode(',', $playerSagData['sag_patterns']));
//        $result['privateobjectives'] = explode(',', $playerSagData['sag_privateobjectives']);


        $result['patterns'] = Patterns::getPatterns([1,2,3,4]);
        $result['privateobjectives'] = ['R'];
//        print "<PRE>" . print_r($result, true) . "</PRE>";
        return $result;
    }
}