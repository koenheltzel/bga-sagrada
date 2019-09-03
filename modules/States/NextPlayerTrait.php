<?php

namespace Sag\States;

use Sag\GameState;

trait NextPlayerTrait {

    public function stNextPlayer() {
        if (count(GameState::get()->roundPlayerTurns) > 0) {
            $playerId = array_shift(GameState::get()->roundPlayerTurns);
            GameState::get()->save();
            $this->gamestate->changeActivePlayer($playerId);
            $this->gamestate->nextState('playerTurn');
        }
        else {
            $this->gamestate->changeActivePlayer(GameState::get()->nextStartPlayer);
            $this->gamestate->nextState('nextRound');
        }
    }
}