<?php

namespace Sag\States;

use Sag\Patterns;
use Sagrada;

trait SelectPatternTrait {

    public function getSelectPatternData($playerId) {
        // Normal gameplay, use self::getCurrentPlayerId() to return real data.
        $sql = "
            SELECT sag_patterns, sag_privateobjectives
            FROM player
            WHERE player_id = {$playerId}";
        $playerSagData = Sagrada::db($sql)->fetch_assoc();

        return [
            'patterns' => Patterns::getPatterns(explode(',', $playerSagData['sag_patterns'])),
            'privateobjectives' => explode(',', $playerSagData['sag_privateobjectives'])
        ];
    }

    public function actionSelectPattern($pattern){
        $current_player_id = self::getCurrentPlayerId();
        $sql = "
            UPDATE player
            SET sag_patterns          = '{$pattern}'
            WHERE player_id = {$current_player_id}
        ";
        self::db($sql);
    }

    public function stSelectPattern() {
//        print "<PRE>" . print_r("stSelectPattern", true) . "</PRE>";
//        $playerId = (int) $this->getCurrentPlayerId();

//        $this->notifyAllPlayers(
//            'selectPattern',
//            "stSelectPattern notification log",
//            $this->getSelectPatternData($playerId)
//        );

//        return $result;
    }
}