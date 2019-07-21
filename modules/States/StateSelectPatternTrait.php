<?php

namespace Sagrada\States;

use Sagrada\Patterns;

trait StateSelectPatternTrait {

    function getSelectPatternData($playerId) {
        // Normal gameplay, use self::getCurrentPlayerId() to return real data.
        $current_player_id = self::getCurrentPlayerId();
        $sql = "
            SELECT sag_patterns, sag_privateobjectives
            FROM player
            WHERE player_id = {$current_player_id}";
        $playerSagData = self::db($sql)->fetch_assoc();

        return [
            'patterns' => Patterns::getPatterns(explode(',', $playerSagData['sag_patterns'])),
            'privateobjectives' => explode(',', $playerSagData['sag_privateobjectives'])
        ];
    }

    function stSelectPattern() {
//        $playerId = (int) $this->getCurrentPlayerId();

//        $this->notifyAllPlayers(
//            'selectPattern',
//            "stSelectPattern notification log",
//            $this->getSelectPatternData($playerId)
//        );

//        return $result;
    }
}