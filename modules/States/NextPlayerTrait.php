<?php

namespace Sag\States;

trait NextPlayerTrait {

    public function stNextPlayer() {
        $this->activeNextPlayer();
        $this->gamestate->nextState('playerTurn');
    }
}