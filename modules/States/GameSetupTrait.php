<?php

namespace Sag\States;

use Sag\Colors;
use Sag\GameState;

trait GameSetupTrait {

    public function stGameSetup() {
//        print "<PRE>" . print_r("stGameSetup", true) . "</PRE>";

//        $players = self::db("SELECT * FROM player ORDER BY player_no")->fetch_all(MYSQLI_ASSOC);
        $players = $this->loadPlayersBasicInfos();
        $playerCount = count($players);
        $patternCount = count($players) * self::PATTERNS_PER_PLAYER;
        $pairCount = $patternCount / 2;

        GameState::get()->init($players);

        // We select the patterns by the pair, because in the real game the patterns are on double sided cards. Don't know if the creators care about preserving these pairs, but BGA strives for authenticity, so there you go.
        $pairs = self::DbQuery("SELECT DISTINCT pair FROM sag_patterns ORDER BY RAND() LIMIT {$pairCount}")->fetch_all();
        $pairIds = implode(',', array_map(function($pair) { return $pair[0] ;}, $pairs));
        // Select the patterns, ordered by the random selected pairs above (and within the pair, sort random).
        $sql = "SELECT * FROM sag_patterns WHERE pair IN ({$pairIds}) ORDER BY FIELD(pair, {$pairIds}), RAND() LIMIT {$patternCount}";
        $patterns = self::DbQuery($sql)->fetch_all(MYSQLI_ASSOC);

        // Select private objective colors.
        $randomColors = null;
        $randomColors = Colors::get()->colors;
        shuffle($randomColors);
        $randomColorChars = array_map(function($randomColor) { return $randomColor->char;}, $randomColors);

        $i = 0;
        foreach ($players AS $id => $player) {
            // Assign 2 random pattern pairs (= 4 patterns) to the player.
            $patternIds = array_map(function($pattern) { return $pattern['id'] ;}, array_slice($patterns, $i * self::PATTERNS_PER_PLAYER, self::PATTERNS_PER_PLAYER) );
            $patternIdsString = implode(',', $patternIds);

            // Assign private objective(s) to the player.
            $privateObjectiveCount = count($players) == 1 ? 2 : 1; // Normally 1 private objective is assigned, but in a solo game 2.
            $privateObjectiveIdsString = implode(',', array_slice($randomColorChars, $i * $privateObjectiveCount, $privateObjectiveCount));

            $player_no = $i + 1;
            $sql = "
                UPDATE player
                SET sag_patterns          = '{$patternIdsString}'
                  , sag_private_objectives = '{$privateObjectiveIdsString}'
                WHERE player_no = {$player_no}
            ";
            self::DbQuery($sql);
            $i++;
        }

        // Activate first player (which is in general a good idea :) )
        self::activeNextPlayer();
        $this->gamestate->setAllPlayersMultiactive();
    }
}