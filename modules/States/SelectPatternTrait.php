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

    public function actionSelectPattern($patternId){
        $playerId = self::getCurrentPlayerId();

        // TODO: Check if this pattern is allowed (aka currently in the sag_patterns list.

        $sql = "
            UPDATE player
            SET sag_patterns          = '{$patternId}'
            WHERE player_id = {$playerId}
        ";
        self::db($sql);

        $pattern = Patterns::getPatterns([$patternId])[0];

        $this->notifyAllPlayers(
            'patternSelected',
            clienttranslate('${playerName} selected pattern ${patternName} (${difficulty})'),
            [
                'patternName' => $pattern->name,
                'difficulty' => substr('**********', 0, $pattern->difficulty),
                'playerName' => $this->getCurrentPlayerName(),
                'playerColor' => $this->getCurrentPlayerColor(),
                'playerId' => $playerId
            ]
        );
//
//        $this->notifyPlayer(
//            $playerId,
//            'iReturnedToDeck',
//            '',
//            ['cardIds' => $cardIds]
//        );

        $this->giveExtraTime($playerId);
        $this->gamestate->setPlayerNonMultiactive($playerId, 'allPatternsSelected');
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