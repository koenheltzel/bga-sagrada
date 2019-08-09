<?php

namespace Sag\States;

use Sag\Boards;
use Sag\DraftPool;

trait PlayerTurnTrait {

    public function getPlayerTurnData() {
        return [
            'boards' => (Boards::get())->boards,
            'draftPool' => DraftPool::get()->dice
        ];
    }

    public function argPlayerTurn() {
        return $this->getPlayerTurnData();
    }

    public function stPlayerTurn() {
        $this->notifyAllPlayers(
            'playerTurn',
            "playerTurn notification log",
            $this->getPlayerTurnData()
        );
    }
}