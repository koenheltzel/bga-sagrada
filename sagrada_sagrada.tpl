{OVERALL_GAME_HEADER}

<style>
    /*html {*/
    /*    background-image: none;*/
    /*}*/

    body {
        font-size: 12px !important;
    }
</style>

<!-- 
--------
-- BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
-- Sagrada implementation : © Koen Heltzel koenheltzel@gmail.com
-- 
-- This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
-- See http://en.boardgamearena.com/#!doc/Studio for more information.
-------

    sagrada_sagrada.tpl
    
    This is the HTML template of your game.
    
    Everything you are writing in this file will be displayed in the HTML page of your game user interface,
    in the "main game zone" of the screen.
    
    You can use in this template:
    _ variables, with the format {MY_VARIABLE_ELEMENT}.
    _ HTML block, with the BEGIN/END format
    
    See your "view" PHP file to check how to set variables and control blocks
    
    Please REMOVE this comment before publishing your game on BGA
-->


<div class="whiteblock" id="pattern_selection">
    <h3>Select a window pattern:</h3>
    <!-- BEGIN pattern_selection_pattern -->
    <div id="pattern_selection_{number}" class="pattern-sprite">
    </div>
    <!-- END pattern_selection_pattern -->
</div>

<div id="board">
    <!-- BEGIN square -->
    <div id="square_{X}_{Y}" class="square" style="left: {LEFT}px; top: {TOP}px;"></div>
    <!-- END square -->

    <div id="dice">
    </div>
</div>


<script type="text/javascript">

    // Javascript HTML templates

    /*
    // Example:
    var jstpl_some_game_item='<div class="my_game_item" id="my_game_item_${id}"></div>';

    */

    var jstpl_die = '<div class="die die_${color}_${value}" id="die_${x_y}"></div>';

</script>

{OVERALL_GAME_FOOTER}
