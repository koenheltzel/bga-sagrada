<?php

namespace Sag\States;

use Sag\Patterns;
use Sagrada;

trait DraftDieTrait {

    public function actionDraftDie($draftPoolId, $color, $value, $x, $y){
        $this->checkAction('actionDraftDie');

        $playerId = self::getCurrentPlayerId();

        // Delete the specified die from the draftpool.
        $sql = "
            DELETE FROM sag_draftpool WHERE id = {$draftPoolId} 
                 AND die_color = '{$color}' 
                 AND die_value = {$value}
        ";
        Sagrada::db($sql);
        if ($this->DbAffectedRow() == 0) {
            throw new BgaUserException('The selected die is not actually in the draft pool.');
        }

        // Add the die to the player's board
        $sql = "
            INSERT INTO sag_boardspace (player_id, x, y, die_color, die_value) VALUES ({$playerId},{$x},{$y},'{$color}',{$value})
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