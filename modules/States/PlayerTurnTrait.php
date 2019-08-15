<?php

namespace Sag\States;

use Sag\Boards;
use Sag\DraftPool;

trait PlayerTurnTrait {

    public function getPlayerTurnData($playerId) {
        return [
            'draftPool' => DraftPool::get()->getDiceWithLegalPositions($playerId)
        ];
    }

    public function notifyPlayersOfDraftPool() {
        $players = $this->loadPlayersBasicInfos();
        foreach ($players AS $playerId => $player) {
            $this->notifyPlayer(
                $playerId,
                'updateDraftPool',
                '',
                [
                    'draftPool' => DraftPool::get()->getDiceWithLegalPositions($playerId)
                ]
            );
        }
    }

    public function stPlayerTurn() {
//        $this->notifyAllPlayers(
//            'playerTurn',
//            "playerTurn notification log",
//            []
//        );

        $this->notifyPlayersOfDraftPool();
    }
}