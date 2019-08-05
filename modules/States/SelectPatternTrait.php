<?php

namespace Sag\States;

use Sag\Patterns;
use Sagrada;

trait SelectPatternTrait {

    public function getSelectPatternData($playerId) {
        // Normal gameplay, use self::getCurrentPlayerId() to return real data.
        $sql = "
            SELECT sag_patterns, sag_private_objectives
            FROM player
            WHERE player_id = {$playerId}";
        $playerSagData = Sagrada::db($sql)->fetch_assoc();

        return [
            'patterns' => Patterns::getPatterns(explode(',', $playerSagData['sag_patterns'])),
            'privateObjectives' => explode(',', $playerSagData['sag_private_objectives'])
        ];
    }

    public function actionSelectPattern($patternId){
        $playerId = self::getCurrentPlayerId();

        $pattern = Patterns::getPattern($patternId);
        // TODO: Check if the selected pattern is actually available to this player (aka currently in the sag_patterns list).

        $sql = "
            UPDATE player
            SET sag_patterns        = '{$patternId}',
                sag_tokens          = '{$pattern->difficulty}'
            WHERE player_id = {$playerId}
        ";
        self::db($sql);


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