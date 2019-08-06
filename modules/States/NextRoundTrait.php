<?php

namespace Sag\States;

use Sag\DraftPool;
use Sagrada;

trait NextRoundTrait {

    public function stNextRound() {
        DraftPool::get()->fill($this->getPlayersNumber() * 2 + 1);

        $this->gamestate->nextState();
    }
}