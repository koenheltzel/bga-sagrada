<?php

namespace Sag\States;

use Sag\Boards;
use Sag\DraftPool;
use Sagrada;

trait DraftDieTrait {

    public function actionDraftDie($draftPoolId, $color, $value, $x, $y){
        $this->checkAction('actionDraftDie');

        $playerId = $this->getActivePlayerId();

        DraftPool::get()->deleteDie($draftPoolId, $color, $value);

        // Add the die to the player's board
        $sql = "
            INSERT INTO sag_boardspace (player_id, x, y, die_color, die_value) VALUES ({$playerId},{$x},{$y},'{$color->char}',{$value})
        ";
        Sagrada::DbQuery($sql);

        $playerName = $this->getActivePlayerName();
        $this->notifyAllPlayers(
            'dieDrafted',
            clienttranslate("${playerName} drafted die {$color->char}{$value}"),
            [
                'draftPool' => DraftPool::get()->dice,
                'draftPoolId' => $draftPoolId,
                'x' => $x,
                'y' => $y,
                'playerId' => $playerId
            ]
        );

        $this->giveExtraTime($playerId);
        $this->gamestate->nextState('nextPlayer');
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