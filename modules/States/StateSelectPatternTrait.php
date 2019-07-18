<?php

namespace Sagrada\States;

use Sagrada\Patterns;

trait StateSelectPatternTrait {

    function argSelectPattern() {
        if(is_numeric(array_shift($this->gamestate->state()['reflexion']['total']))) {
            // Normal gameplay, use self::getCurrentPlayerId() to return real data.
            $current_player_id = self::getCurrentPlayerId();
            $sql = "
                SELECT sag_patterns, sag_privateobjectives
                FROM player
                WHERE player_id = {$current_player_id}";
            $playerSagData = self::db($sql)->fetch_assoc();
            $result['patterns'] = Patterns::getPatterns(explode(',', $playerSagData['sag_patterns']));
            $result['privateobjectives'] = explode(',', $playerSagData['sag_privateobjectives']);
        }
        else {
            // Initial setup, return dummy data without using self::getCurrentPlayerId().
            $result['patterns'] = Patterns::getPatterns([1,2,3,4]);
            $result['privateobjectives'] = ['R'];
        }


if(is_numeric(array_shift($this->gamestate->state()['reflexion']['total']))) {
    // Normal gameplay, use self::getCurrentPlayerId() to return real data.
}
else {
    // Initial setup, return dummy data without using self::getCurrentPlayerId().
}

//        print "<PRE>" . print_r($result, true) . "</PRE>";
        return $result;
    }
}