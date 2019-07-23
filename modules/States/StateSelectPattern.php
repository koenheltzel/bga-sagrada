<?php

namespace Sagrada\States;

use Sagrada\Patterns;

class StateSelectPattern {

    const ID = 3;

    public static function getData($playerId) {
        // Normal gameplay, use self::getCurrentPlayerId() to return real data.
        $sql = "
            SELECT sag_patterns, sag_privateobjectives
            FROM player
            WHERE player_id = {$playerId}";
        $playerSagData = \Sagrada::db($sql)->fetch_assoc();

        return [
            'patterns' => Patterns::getPatterns(explode(',', $playerSagData['sag_patterns'])),
            'privateobjectives' => explode(',', $playerSagData['sag_privateobjectives'])
        ];
    }
}