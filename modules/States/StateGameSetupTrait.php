<?php

namespace Sagrada\States;

use Sagrada\Colors;
use Sagrada\Patterns;

trait StateGameSetupTrait {

    public function stGameSetup() {
//        print "<PRE>" . print_r("stGameSetup", true) . "</PRE>";
        // Fill the dice bag.
        foreach (Colors::get()->colors as $color) {
            self::setGameStateInitialValue(self::GAMESTATE_DICEBAG . $color->char, 18);
        }

//        $players = self::db("SELECT * FROM player ORDER BY player_no")->fetch_all(MYSQLI_ASSOC);
        $players = $this->loadPlayersBasicInfos();
        $playerCount = count($players);
        $patternCount = count($players) * self::PATTERNS_PER_PLAYER;
        $pairCount = $patternCount / 2;

        $publicObjectivesCount = count($players) > 1 ? 3 : 2; // Normally 3 public objectives are assigned, but in a solo game 2.
        $publicObjectives = self::db("SELECT * FROM sag_publicobjectives ORDER BY RAND() LIMIT {$publicObjectivesCount}")->fetch_all(MYSQLI_ASSOC);
        $publicObjectivesIds = array_map(function($publicObjective) { return $publicObjective['id'] ;}, $publicObjectives );
        $publicObjectivesIdsString = implode(',', $publicObjectivesIds);
//        self::setGameStateInitialValue(self::GAMESTATE_PUBLICOBJECTIVES, $publicObjectivesIdsString);

        // We select the patterns by the pair, because in the real game the patterns are on double sided cards. Don't know if the creators care about preserving these pairs, but BGA strives for authenticity, so there you go.
        $pairs = self::db("SELECT DISTINCT pair FROM sag_patterns ORDER BY RAND() LIMIT {$pairCount}")->fetch_all();
        $pairIds = implode(',', array_map(function($pair) { return $pair[0] ;}, $pairs));
        // Select the patterns, ordered by the random selected pairs above (and within the pair, sort random).
        $sql = "SELECT * FROM sag_patterns WHERE pair IN ({$pairIds}) ORDER BY FIELD(pair, {$pairIds}), RAND() LIMIT {$patternCount}";
        $patterns = self::db($sql)->fetch_all(MYSQLI_ASSOC);

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
                  , sag_privateobjectives = '{$privateObjectiveIdsString}'
                WHERE player_no = {$player_no}
            ";
            self::db($sql);
            $i++;
        }

        // Activate first player (which is in general a good idea :) )
        self::activeNextPlayer();
        $this->gamestate->setAllPlayersMultiactive();
    }
}