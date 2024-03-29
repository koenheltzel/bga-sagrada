<?php

namespace Sag\States;

use Sag\DraftPool;
use Sag\GameState;
use Sagrada;

trait NextRoundTrait {

    public function stNextRound() {
        DraftPool::get()->fill($this->getPlayersNumber() * 2 + 1);

        $nextPlayerTable = Sagrada::get()->getNextPlayerTable();

        $roundPlayerTurns = [];
        $tmpPlayerId = GameState::get()->nextStartPlayer;
        unset($nextPlayerTable[0]);
        $count = count($nextPlayerTable);
        for($i = 0; $i < $count; $i++) {
            $roundPlayerTurns[] = $tmpPlayerId;
            $tmpPlayerId = $nextPlayerTable[$tmpPlayerId];
        }
        $roundPlayerTurns = array_merge($nextPlayerTable, array_reverse($nextPlayerTable));
        GameState::get()->roundPlayerTurns = $roundPlayerTurns;

        GameState::get()->nextStartPlayer = $roundPlayerTurns[1];

        GameState::get()->save();

        $this->gamestate->nextState();
    }
}