<?php

use Sag\Colors;

/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * Sagrada implementation : © Koen Heltzel <koenheltzel@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * sagrada.action.php
 *
 * Sagrada main action entry point
 *
 *
 * In this file, you are describing all the methods that can be called from your
 * user interface logic (javascript).
 *
 * If you define a method "myAction" here, then you can call it from your javascript code with:
 * this.ajaxcall( "/sagrada/sagrada/myAction.html", ...)
 *
 */


class action_sagrada extends APP_GameAction {

    // Constructor: please do not modify
    public function __default() {
        if (self::isArg('notifwindow')) {
            $this->view = "common_notifwindow";
            $this->viewArgs['table'] = self::getArg("table", AT_posint, true);
        } else {
            $this->view = "sagrada_sagrada";
            self::trace("Complete reinitialization of board game");
        }
    }

    // TODO: defines your action entry points there

    public function actionSelectPattern() {
        self::setAjaxMode();

        $pattern = self::getArg("pattern", AT_posint, true);
        $this->game->actionSelectPattern($pattern);

        self::ajaxResponse();
    }

    public function actionDraftDie() {
        self::setAjaxMode();

        $draftPoolId = self::getArg("draftPoolId", AT_posint, true);
        $color = Colors::get()->getColor(self::getArg("color", AT_alphanum, true));
        $value = self::getArg("value", AT_posint, true);
        $x = self::getArg("x", AT_posint, true);
        $y = self::getArg("y", AT_posint, true);
        $this->game->actionDraftDie($draftPoolId, $color, $value, $x, $y);

        self::ajaxResponse();
    }

    /*
    
    Example:
  	
    public function myAction()
    {
        self::setAjaxMode();     

        // Retrieve arguments
        // Note: these arguments correspond to what has been sent through the javascript "ajaxcall" method
        $arg1 = self::getArg( "myArgument1", AT_posint, true );
        $arg2 = self::getArg( "myArgument2", AT_posint, true );

        // Then, call the appropriate method in your game logic, like "playCard" or "myAction"
        $this->game->myAction( $arg1, $arg2 );

        self::ajaxResponse( );
    }
    
    */

}
  

