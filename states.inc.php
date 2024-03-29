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
 * states.inc.php
 *
 * Sagrada game states description
 *
 */

/*
   Game state machine is a tool used to facilitate game developpement by doing common stuff that can be set up
   in a very easy way from this configuration file.

   Please check the BGA Studio presentation about game state to understand this, and associated documentation.

   Summary:

   States types:
   _ activeplayer: in this type of state, we expect some action from the active player.
   _ multipleactiveplayer: in this type of state, we expect some action from multiple players (the active players)
   _ game: this is an intermediary state where we don't expect any actions from players. Your game logic must decide what is the next game state.
   _ manager: special type for initial and final state

   Arguments of game states:
   _ name: the name of the GameState, in order you can recognize it on your own code.
   _ description: the description of the current game state is always displayed in the action status bar on
                  the top of the game. Most of the time this is useless for game state with "game" type.
   _ descriptionmyturn: the description of the current game state when it's your turn.
   _ type: defines the type of game states (activeplayer / multipleactiveplayer / game / manager)
   _ action: name of the method to call when this game state become the current game state. Usually, the
             action method is prefixed by "st" (ex: "stMyGameStateName").
   _ possibleactions: array that specify possible player actions on this step. It allows you to use "checkAction"
                      method on both client side (Javacript: this.checkAction) and server side (PHP: self::checkAction).
   _ transitions: the transitions are the possible paths to go from a game state to another. You must name
                  transitions in order to use transition names in "nextState" PHP method, and use IDs to
                  specify the next game state for each transition.
   _ args: name of the method to call to retrieve arguments for this gamestate. Arguments are sent to the
           client side to be used on "onEnteringState" or to set arguments in the gamestate description.
   _ updateGameProgression: when specified, the game progression is updated (=> call to your getGameProgression
                            method).
*/

//    !! It is not a good idea to modify this file when a game is running !!


use Sag\States\StateGameSetup;
use Sag\States\StateSelectPattern;

$machinestates = array(

    // The initial state. Please do not modify.
    // 1
    Sagrada::STATE_GAME_SETUP => array(
        "name" => "gameSetup",
        "description" => "",
        "type" => "manager",
        "transitions" => array( /*"soloSelectDifficulty" => 2,*/ "" => Sagrada::STATE_SELECT_PATTERN )
    ),
    
    // Note: ID=2 => your first state

//    2 => array(
//        "name" => "soloSelectDifficulty",
//        "description" => clienttranslate('${actplayer} must select a difficulty level'),
//        "descriptionmyturn" => clienttranslate('${you} must select a difficulty level'),
//        "type" => "activeplayer",
//        "action" => "stSoloSelectDifficulty",
//        "possibleactions" => array( "soloSelectDifficulty" ),
//        "transitions" => array( "soloDifficultySelected" => 3 )
//    ),

    // 3
    Sagrada::STATE_SELECT_PATTERN => array(
        "name" => "selectPattern",
        "description" => clienttranslate('Wait for other players to select a window pattern'),
        "descriptionmyturn" => clienttranslate('${you} must select a window pattern'),
        "type" => "multipleactiveplayer",
        "action" => "stSelectPattern",
        "possibleactions" => array( "actionSelectPattern" ),
        "transitions" => array( "allPatternsSelected" => Sagrada::STATE_NEXT_ROUND ),
//        "args" => 'argSelectPattern',
    ),

    // 5
    Sagrada::STATE_NEXT_ROUND => array(
        "name" => "nextRound",
        "description" => '',
        "type" => "game",
        "action" => "stNextRound",
        "updateGameProgression" => true,
        "transitions" => array("" => Sagrada::STATE_NEXT_PLAYER )
    ),

    Sagrada::STATE_NEXT_PLAYER => array(
        "name" => "nextPlayer",
        "description" => '',
        "type" => "game",
        "action" => "stNextPlayer",
        "updateGameProgression" => true,
        "transitions" => array( "endGame" => 99, "playerTurn" => Sagrada::STATE_PLAYER_TURN, "nextRound" => Sagrada::STATE_NEXT_ROUND)
    ),

    // 10
    Sagrada::STATE_PLAYER_TURN => array(
        "name" => "playerTurn",
        "description" => clienttranslate('${actplayer} must draft a die and/or use a tool'),
        "descriptionmyturn" => clienttranslate('${you} must draft a die and/or use a tool in any order'),
        "type" => "activeplayer",
        "action" => "stPlayerTurn",
        "possibleactions" => array( "actionDraftDie", "actionUseTool", "actionPass" ),
        "transitions" => array( "draftDie" => 20,  "useTool" => 30, "pass" => 50, "nextPlayer" => Sagrada::STATE_NEXT_PLAYER )
    ),

    20 => array(
        "name" => "draftDie",
        "description" => clienttranslate('${actplayer} must draft a die'),
        "descriptionmyturn" => clienttranslate('${you} must draft a die'),
        "type" => "activeplayer",
        "possibleactions" => array( "draftDie", "back" ),
        "transitions" => array( "nextPlayer" => Sagrada::STATE_NEXT_PLAYER, "back" => Sagrada::STATE_PLAYER_TURN )
    ),

    21 => array(
        "name" => "placeDie",
        "description" => clienttranslate('${actplayer} must place the die'),
        "descriptionmyturn" => clienttranslate('${you} must place the die'),
        "type" => "activeplayer",
        "possibleactions" => array( "playerTurn", "back" ),
        "transitions" => array( "playerTurn" => Sagrada::STATE_PLAYER_TURN, "back" => 10 )
    ),

    30 => array(
        "name" => "useTool",
        "description" => clienttranslate('${actplayer} must select a tool'),
        "descriptionmyturn" => clienttranslate('${you} must select a tool'),
        "type" => "activeplayer",
        "possibleactions" => array( "selectTool", "back" ),
        "transitions" => array("selectTool" => 12, "back" => Sagrada::STATE_PLAYER_TURN )
    ),

    31 => array(
        "name" => "soloPayTool",
        "description" => clienttranslate('${actplayer} must select a die from the draft pool to pay for the tool'),
        "descriptionmyturn" => clienttranslate('${you} must select a die from the draft pool to pay for the tool'),
        "type" => "activeplayer",
        "possibleactions" => array( "soloPayTool", "back" ),
        "transitions" => array( "useTool" => 12, "back" => Sagrada::STATE_PLAYER_TURN )
    ),
    
/*
    Examples:
    
    2 => array(
        "name" => "nextPlayer",
        "description" => '',
        "type" => "game",
        "action" => "stNextPlayer",
        "updateGameProgression" => true,
        "transitions" => array( "endGame" => 99, "nextPlayer" => 10 )
    ),
    
    10 => array(
        "name" => "playerTurn",
        "description" => clienttranslate('${actplayer} must play a card or pass'),
        "descriptionmyturn" => clienttranslate('${you} must play a card or pass'),
        "type" => "activeplayer",
        "possibleactions" => array( "playCard", "pass" ),
        "transitions" => array( "playCard" => 2, "pass" => 2 )
    ), 

*/    
   
    // Final state.
    // Please do not modify.
    99 => array(
        "name" => "gameEnd",
        "description" => clienttranslate("End of game"),
        "type" => "manager",
        "action" => "stGameEnd",
        "args" => "argGameEnd"
    )

);



