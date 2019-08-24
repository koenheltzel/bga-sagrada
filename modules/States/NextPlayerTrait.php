<?php

namespace Sag\States;

trait NextPlayerTrait {

    public function stNextPlayer() {
        $this->activeNextPlayer();
        //$this->gamestate->changeActivePlayer($active_player);
        $this->gamestate->nextState('playerTurn');
    }
}