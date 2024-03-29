<?php

namespace Sag\States;

use Sag\Boards;
use Sag\Patterns;
use Sagrada;

trait SelectPatternTrait {

    public function getSelectPatternData($playerId) {
        // Normal gameplay, use self::getCurrentPlayerId() to return real data.
        $sql = "
            SELECT sag_patterns, sag_private_objectives
            FROM player
            WHERE player_id = {$playerId}";
        $playerSagData = Sagrada::DbQuery($sql)->fetch_assoc();

        return [
            'patterns' => Patterns::getPatterns(explode(',', $playerSagData['sag_patterns'])),
            'privateObjectives' => explode(',', $playerSagData['sag_private_objectives'])
        ];
    }

    public function actionSelectPattern($patternId){
        $this->checkAction('actionSelectPattern');

        $playerId = self::getCurrentPlayerId();

        $pattern = Patterns::getPattern($patternId);
        // TODO: Check if the selected pattern is actually available to this player (aka currently in the sag_patterns list).

        $sql = "
            UPDATE player
            SET sag_patterns        = '{$patternId}',
                sag_tokens          = '{$pattern->difficulty}'
            WHERE player_id = {$playerId}
        ";
        self::DbQuery($sql);


        $this->notifyAllPlayers(
            'patternSelected',
            clienttranslate('${playerName} selected pattern ${patternName} (${difficulty})'),
            [
                'boards' => Boards::get()->boards,
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
//
//        $this->notifyPlayer(
//            $playerId,
//            'selectPattern',
//            "stSelectPattern notification log",
//            $this->getSelectPatternData($playerId)
//        );

//        return $result;
    }
}