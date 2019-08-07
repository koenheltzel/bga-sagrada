<?php

namespace Sag\States;

use Sag\Patterns;
use Sagrada;

trait DraftDieTrait {

    public function actionDraftDie($id, $color, $value){
        $this->checkAction('actionDraftDie');

        $playerId = self::getCurrentPlayerId();

        $sql = "
            SELECT *
            FROM sag_draftpool
            WHERE
                 id = {$id} 
                 AND die_color = '{$color}' 
                 AND die_value = {$value}
        ";
        $result = Sagrada::db($sql);
        if ($result->num_rows == 0) {
            throw new BgaUserException('Die is not in draft pool.');
        }

        $sql = "
            DELETE FROM sag_draftpool WHERE id = {$id}
        ";
        Sagrada::db($sql);

//        $this->notifyAllPlayers(
//            'patternSelected',
//            clienttranslate('${playerName} selected pattern ${patternName} (${difficulty})'),
//            [
//                'patternName' => $pattern->name,
//                'difficulty' => substr('**********', 0, $pattern->difficulty),
//                'playerName' => $this->getCurrentPlayerName(),
//                'playerColor' => $this->getCurrentPlayerColor(),
//                'playerId' => $playerId
//            ]
//        );
//
//        $this->notifyPlayer(
//            $playerId,
//            'iReturnedToDeck',
//            '',
//            ['cardIds' => $cardIds]
//        );

//        $this->giveExtraTime($playerId);
//        $this->gamestate->setPlayerNonMultiactive($playerId, 'allPatternsSelected');
    }

    public function stDraftDie() {
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