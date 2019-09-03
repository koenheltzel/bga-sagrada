<?php

namespace Sag\States;

use Sag\DraftPool;
use Sagrada;

trait NextRoundTrait {

    public function stNextRound() {
        DraftPool::get()->fill($this->getPlayersNumber() * 3 + 1);

        $nextPlayerTable = Sagrada::get()->getNextPlayerTable();

        $roundPlayerTable = [];
        $tmpPlayerId = $nextPlayerTable[0];
        $count = count($nextPlayerTable);
        for($i = 0; $i < $count; $i++) {
            $roundPlayerTable[] = $tmpPlayerId;
            $tmpPlayerId = $nextPlayerTable[$tmpPlayerId];
        }
        $roundPlayerTable = array_merge($nextPlayerTable, array_reverse($nextPlayerTable));

        $this->gamestate->nextState();
    }
}