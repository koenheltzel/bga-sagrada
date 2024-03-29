<?php
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * Sagrada implementation : © Koen Heltzel <koenheltzel@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * sagrada.view.php
 *
 * This is your "view" file.
 *
 * The method "build_page" below is called each time the game interface is displayed to a player, ie:
 * _ when the game starts
 * _ when a player refreshes the game page (F5)
 *
 * "build_page" method allows you to dynamically modify the HTML generated for the game interface. In
 * particular, you can set here the values of variables elements defined in sagrada_sagrada.tpl (elements
 * like {MY_VARIABLE_ELEMENT}), and insert HTML block elements (also defined in your HTML template file)
 *
 * Note: if the HTML of your game interface is always the same, you don't have to place anything here.
 *
 */

use Sag\Colors;
use Sag\PlayerData;
use Sag\PlayerDatas;
use Sag\PrivateObjectives;
use Sag\PublicObjectives;

require_once(APP_BASE_PATH . "view/common/game.view.php");

class view_sagrada_sagrada extends game_view {

    function getGameName() {
        return "sagrada";
    }

    function build_page($viewArgs) {

//        print str_repeat("<br/>", 100);

        // Get players & players number
        $players = $this->game->loadPlayersBasicInfos();

        $players_nbr = count($players);

        /*********** Place your code below:  ************/
        $this->page->begin_block("sagrada_sagrada", "square");
        $this->page->begin_block("sagrada_sagrada", "board");

        foreach($players as $player) {
            $this->page->reset_subblocks( 'square' );

            $hor_scale = 44.5;
            $ver_scale = 44.5;
            for ($x = 0; $x < 5; $x++) {
                for ($y = 0; $y < 4; $y++) {
                    $this->page->insert_block("square", [
                        'X' => $x,
                        'Y' => $y,
                        'LEFT' => round(20 + $x * ($hor_scale)),
                        'TOP' => round(380 + $y * ($ver_scale)),
                        'playerId' => $player['player_id']
                    ]);
                }
            }

            $this->page->insert_block("board", [
                'playerName' => $player['player_name'],
                'playerId' => $player['player_id'],
                'playerColor' => $player['player_color'],
                'playerColorChar' => Colors::getColorByHex($player['player_color'])->char,
            ]);
        }

        $this->page->begin_block("sagrada_sagrada", "publicobjective");
        foreach (PublicObjectives::getPublicObjectives() as $publicObjective) {
            $this->page->insert_block("publicobjective", [
                'id' => $publicObjective->id,
                'name' => $publicObjective->name,
                'description' => $publicObjective->description,
                'points' => $publicObjective->points
            ]);
        }

        $playerData = PlayerDatas::get()->getPlayerData($this->game->getCurrentPlayerId());

        $this->page->begin_block("sagrada_sagrada", "privateobjective");
        foreach ($playerData->privateObjectives as $privateObjective) {
            $this->page->insert_block("privateobjective", [
                'description' => $privateObjective->description,
                'char' => $privateObjective->color->char,
                'color' => $privateObjective->color->name,
                'hex' => $privateObjective->color->hexColor
            ]);
        }

        $this->page->begin_block("sagrada_sagrada", "pattern_selection_pattern");
        for ($i = 1; $i <= 4; $i++) {
            $this->page->insert_block("pattern_selection_pattern", [
                'number' => $i
            ]);
        }


        /*
        
        // Examples: set the value of some element defined in your tpl file like this: {MY_VARIABLE_ELEMENT}

        // Display a specific number / string
        $this->tpl['MY_VARIABLE_ELEMENT'] = $number_to_display;

        // Display a string to be translated in all languages: 
        $this->tpl['MY_VARIABLE_ELEMENT'] = self::_("A string to be translated");

        // Display some HTML content of your own:
        $this->tpl['MY_VARIABLE_ELEMENT'] = self::raw( $some_html_code );
        
        */

        /*
        
        // Example: display a specific HTML block for each player in this game.
        // (note: the block is defined in your .tpl file like this:
        //      <!-- BEGIN myblock --> 
        //          ... my HTML code ...
        //      <!-- END myblock --> 
        

        $this->page->begin_block( "sagrada_sagrada", "myblock" );
        foreach( $players as $player )
        {
            $this->page->insert_block( "myblock", array( 
                                                    "PLAYER_NAME" => $player['player_name'],
                                                    "SOME_VARIABLE" => $some_value
                                                    ...
                                                     ) );
        }
        
        */


        /*********** Do not change anything below this line  ************/
    }
}
  

