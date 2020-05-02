/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * Sagrada implementation : © Koen Heltzel <koenheltzel@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * sagrada.js
 *
 * Sagrada user interface script
 *
 * In this file, you are describing the logic of your user interface, in Javascript language.
 *
 */

define([
        "dojo",
        "dojo/_base/declare",
        "dojo/on",
        "dojo/_base/lang",
        "dojo/dom-attr",
        "dojo/dom-style",
        "dojo/NodeList-dom",
        "ebg/core/gamegui",
        "ebg/counter"
    ],
    function (dojo, declare, on, lang, domAttr, domStyle) {
        return declare("bgagame.sagrada", ebg.core.gamegui, {
            constructor: function () {
                console.log('sagrada constructor');

                this.activeDie = null;
                this.draftPool = null;
                // Here, you can init the global variables of your user interface
                // Example:
                // this.myGlobalValue = 0;

            },

            /*
                setup:

                This method must set up the game user interface according to current game situation specified
                in parameters.

                The method is called each time the game interface is displayed to a player, ie:
                _ when the game starts
                _ when a player refreshes the game page (F5)

                "gamedatas" argument contains all datas retrieved by your "getAllDatas" PHP method.
            */

            setup: function (gamedatas) {
                console.log("Starting game setup", gamedatas);

                let patterns = dojo.query('.pattern-sprite');
                for (let i = 0; i < patterns.length; i++) {
                    dojo.connect(patterns[i], 'onclick', this, this.onSelectPatternClick);
                }

                on(dojo.query('#draftpool'), ".draftpool-die:click", lang.hitch(this, "onDraftPoolDieClick"));

                this.selectPatternSetup(gamedatas);
                // TODO: Set up your game interface here, according to "gamedatas"

                if (gamedatas.boards) {
                    this.updateBoards(gamedatas.boards);
                }

                if (gamedatas.draftPool) {
                    this.updateDraftPool(gamedatas.draftPool);
                }

                // Setup game notifications to handle (see "setupNotifications" method below)
                this.setupNotifications();

                console.log("Ending game setup");
            },

            selectPatternSetup: function (data) {
                let patterns = data.patterns;
                if (patterns !== undefined && patterns.length == 4) {
                    console.log('Patterns to select between: ', patterns);

                    for (let i = 1; i <= 4; i++) {
                        let pattern = patterns[i - 1];
                        let div = dojo.query('#pattern_selection_' + i);
                        div.addClass('pattern-sprite-' + pattern.id);
                        div.attr('data-id', pattern.id);
                        div.attr('title', pattern.name + ', difficulty ' + pattern.difficulty);
                    }
                    dojo.style('pattern_selection', 'display', 'block');
                }
            },

            ///////////////////////////////////////////////////
            //// Game & client states

            // onEnteringState: this method is called each time we are entering into a new game state.
            //                  You can use this method to perform some user interface changes at this moment.
            //
            onEnteringState: function (stateName, args) {
                console.log('Entering state:', stateName, 'args:', args);

                switch (stateName) {
                    case 'selectPattern':
                        break;
                    case 'playerTurn':
                        break;

                    /* Example:

                    case 'myGameState':

                        // Show some HTML block at this game state
                        dojo.style( 'my_html_block_id', 'display', 'block' );

                        break;
                   */
                }

            },

            // onLeavingState: this method is called each time we are leaving a game state.
            //                 You can use this method to perform some user interface changes at this moment.
            //
            onLeavingState: function (stateName) {
                console.log('Leaving state: ' + stateName);

                switch (stateName) {

                    /* Example:

                    case 'myGameState':

                        // Hide the HTML block we are displaying only during this game state
                        dojo.style( 'my_html_block_id', 'display', 'none' );

                        break;
                   */


                    case 'dummmy':
                        break;
                }
            },

            // onUpdateActionButtons: in this method you can manage "action buttons" that are displayed in the
            //                        action status bar (ie: the HTML links in the status bar).
            //
            onUpdateActionButtons: function (stateName, args) {
                console.log('onUpdateActionButtons: ' + stateName);

                if (this.isCurrentPlayerActive()) {
                    switch (stateName) {
                        /*
                                         Example:

                                         case 'myGameState':

                                            // Add 3 action buttons in the action status bar:

                                            this.addActionButton( 'button_1_id', _('Button 1 label'), 'onMyMethodToCall1' );
                                            this.addActionButton( 'button_2_id', _('Button 2 label'), 'onMyMethodToCall2' );
                                            this.addActionButton( 'button_3_id', _('Button 3 label'), 'onMyMethodToCall3' );
                                            break;
                        */
                    }
                }
            },

            ///////////////////////////////////////////////////
            //// Utility methods

            /*

                Here, you can defines some utility methods that you can use everywhere in your javascript
                script.

            */

            getActiveBoardId: function() {
                return 'board-' + this.getActivePlayerId();
            },

            getCurrentBoardId: function() {
                return 'board-' + this.player_id;
            },

            getDieFromDraftPool: function (id) {
                for (let i = 0; i < this.draftPool.length; i++) {
                    if(id == this.draftPool[i].draftPoolId) {
                        return this.draftPool[i];
                    }
                }
            },

            updateDraftPool: function (draftPool) {
                console.log('updateDraftPool, ', draftPool);
                this.draftPool = draftPool;
                dojo.empty("draftpool");

                for (var i = 0; i < draftPool.length; i++) {
                    var die = draftPool[i];
                    var legalPositionsString = die.draftLegalPositions.length + ' placement options';
                    if (die.draftLegalPositions.length == 0){
                        legalPositionsString = 'No placement options';
                    }
                    else if (die.draftLegalPositions.length == 1){
                        legalPositionsString = '1 placement option';
                    }

                    dojo.place(this.format_block('jstpl_draftpool_die', {
                        jsI: i,
                        jsLeft: i * 40 + 10,
                        jsId: die.draftPoolId,
                        jsLegalPositionsString: legalPositionsString,
                        jsLegalPositions: JSON.stringify(die.draftLegalPositions),
                        jsColor: die.color.char,
                        jsValue: die.value,
                    }), 'draftpool');
                }
                dojo.style('draftpool-container', 'display', draftPool.length > 0 ? 'inline-block' : 'none');
            },

            updateBoards: function (boards) {
                this.boards = boards;
                for (var playerId in boards) {
                    let board = boards[playerId];
                    dojo.query('#board-' + playerId + " .die").forEach(dojo.destroy);
                    // dojo.query('#pattern-' + playerId).forEach(dojo.destroy);
                    dojo.removeClass('pattern-' + playerId);
                    console.log('updateBoards player ', playerId, board.pattern)
                    if (board.pattern) {
                        dojo.addClass('pattern-' + playerId, 'pattern-sprite pattern-sprite-' + board.pattern.id);
                    }
                    else {
                        dojo.addClass('pattern-' + playerId, 'pattern-blank pattern-sprite');
                    }

                    for (var y in board.spaces) {
                        for (var x in board.spaces[y]) {
                            let boardSpace = board.spaces[y][x];
                            if (boardSpace.die !== null) {
                                //TODO: use addDieToBoard here by making that function more flebible?
                                dojo.place(this.format_block('jstpl_die', {
                                    jsXY: x + '_' + y,
                                    jsColor: boardSpace.die.color.char,
                                    jsValue: boardSpace.die.value,
                                    jsPlayerId: playerId,
                                }), playerId + '_dice');

                                this.placeOnObject(playerId + '_die_' + x + '_' + y, playerId + '_square_' + x + '_' + y);
                            }
                        }
                    }

                    // Add random dice for testing purposes.
                    // let colors = ['R', 'G', 'B', 'Y', 'P'];
                    // for (let x = 0; x < 5; x++) {
                    //     for (let y = 0; y < 4; y++) {
                    //         let color = colors[Math.floor(Math.random() * colors.length)];
                    //         let value = Math.floor(Math.random() * (6 - 1 + 1)) + 1;
                    //         dojo.place(this.format_block('jstpl_die', {
                    //             x_y: x + '_' + y,
                    //             color: color,
                    //             value: value,
                    //             jsPlayerId: playerId,
                    //         }), playerId + '_dice');
                    //
                    //         this.placeOnObject(playerId + '_die_' + x + '_' + y, playerId + '_square_' + x + '_' + y);
                    //     }
                    // }
                }
            },


            ///////////////////////////////////////////////////
            //// Player's action

            /*

                Here, you are defining methods to handle player's action (ex: results of mouse click on
                game objects).

                Most of the time, these methods:
                _ check the action is possible at this game state.
                _ make a call to the game server

            */

            addDieToBoard: function (draftPoolId, x, y, color, value, playerId) {
                dojo.place(this.format_block('jstpl_die', {
                    jsXY: x + '_' + y,
                    jsColor: color,
                    jsValue: value,
                    jsPlayerId: playerId,
                }), playerId + '_dice');

                console.log('placeOnObject', playerId + '_die_' + x + '_' + y, 'die_' + draftPoolId);
                this.placeOnObject(playerId + '_die_' + x + '_' + y, 'die_' + draftPoolId);
                this.slideToObject(playerId + '_die_' + x + '_' + y, playerId + '_square_' + x + '_' + y).play();
            },

            /* Example:

            onMyMethodToCall1: function( evt )
            {
                console.log( 'onMyMethodToCall1' );

                // Preventing default browser reaction
                dojo.stopEvent( evt );

                // Check that this action is possible (see "possibleactions" in states.inc.php)
                if( ! this.checkAction( 'myAction' ) )
                {   return; }

                this.ajaxcall( "/sagrada/sagrada/myAction.html", {
                                                                        lock: true,
                                                                        myArgument1: arg1,
                                                                        myArgument2: arg2,
                                                                        ...
                                                                     },
                             this, function( result ) {

                                // What to do after the server call if it succeeded
                                // (most of the time: nothing)

                             }, function( is_error) {

                                // What to do after the server call in anyway (success or failure)
                                // (most of the time: nothing)

                             } );
            },

            */
            onSelectPatternClick: function (e) {
                // Preventing default browser reaction
                dojo.stopEvent(e);

                let pattern = dojo.query(e.target);
                console.log('pattern ', pattern);
                console.log('data-id ', pattern.attr('data-id').pop());

                // Check that this action is possible (see "possibleactions" in states.inc.php)
                if (!this.checkAction('actionSelectPattern')) {
                    return;
                }

                this.ajaxcall("/sagrada/sagrada/actionSelectPattern.html", {
                        pattern: pattern.attr('data-id')
                    },
                    this, function (result) {
                        console.log('success result: ', result);
                        // What to do after the server call if it succeeded
                        // (most of the time: nothing)

                        // Hide pattern selection
                        dojo.style('pattern_selection', 'display', 'none');

                    }, function (is_error) {
                        console.log('error result: ', is_error);
                        // What to do after the server call in anyway (success or failure)
                        // (most of the time: nothing)

                    });
            },

            onDraftPoolDieClick: function (e) {
                // Preventing default browser reaction
                dojo.stopEvent(e);

                let selectedDie = this.getDieFromDraftPool(domAttr.get(e.target, 'data-id'));

                // Deselect previous selection.
                dojo.query("#draftpool .active_die").removeClass("active_die");

                // Remove previous click handlers and legalPositon borders.
                dojo.query('#' + this.getCurrentBoardId() + ' .square').removeClass('legalPosition');
                dojo.query('#' + this.getCurrentBoardId() + ' .square').style('border-color', 'none');
                dojo.query('#' + this.getCurrentBoardId() + ' .square').forEach(function (node) {
                    // node.removeClass('legalPosition');
                    if (typeof node._connectHandlers != "undefined") {
                        dojo.forEach(node._connectHandlers, "dojo.disconnect(item)");
                    }
                });

                if (selectedDie == this.activeDie) {
                    this.activeDie = null;
                }
                else {
                    // Select die
                    dojo.addClass(e.target.id, 'active_die');
                    dojo.style(e.target.id, "border-color", '#' + selectedDie.color.hexColor);

                    this.activeDie = this.getDieFromDraftPool(domAttr.get(e.target, 'data-id'));

                    for (let i = 0; i < this.activeDie.draftLegalPositions.length; i++) {
                        let id = this.player_id + "_square_" + this.activeDie.draftLegalPositions[i][0] + "_" + this.activeDie.draftLegalPositions[i][1];

                        dojo.query('#' + id).addClass('legalPosition');
                        dojo.query('#' + id).style('border-color', '#' + selectedDie.color.hexColor);

                        if (this.player_id == this.getActivePlayerId()) {
                            console.log('id: ', id);
                            let boardSpace = dojo.query("#" + id)[0];
                            boardSpace._connectHandlers = [];
                            boardSpace._connectHandlers.push(dojo.connect(boardSpace, 'onclick', this, this.onBoardSpaceClick));
                        }
                    }
                }
            },

            onBoardSpaceClick: function (e) {
                // Preventing default browser reaction
                dojo.stopEvent(e);

                console.log('onBoardSpaceClick');

                let boardSpace = dojo.query(e.target);

                // Check that this action is possible (see "possibleactions" in states.inc.php)
                if (!this.checkAction('actionDraftDie')) {
                    return;
                }

                dojo.query('#' + this.getCurrentBoardId() + ' .square').removeClass('legalPosition');
                if (this.player_id == this.getActivePlayerId()) {
                    dojo.query('#' + this.getActiveBoardId() + ' .square').forEach(function (node) {
                        if (typeof node._connectHandlers != "undefined") {
                            dojo.forEach(node._connectHandlers, "dojo.disconnect(item)");
                        }
                    });
                }

                this.ajaxcall("/sagrada/sagrada/actionDraftDie.html", {
                        draftPoolId: this.activeDie.draftPoolId,
                        color: this.activeDie.color.char,
                        value: this.activeDie.value,
                        x: boardSpace.attr('data-x'),
                        y: boardSpace.attr('data-y')
                    },
                    this, function (result) {
                        console.log('success result: ', result);
                        // What to do after the server call if it succeeded
                        // (most of the time: nothing)

                    }, function (is_error) {
                        console.log('error result: ', is_error);
                        // What to do after the server call in anyway (success or failure)
                        // (most of the time: nothing)

                    });
            },


            ///////////////////////////////////////////////////
            //// Reaction to cometD notifications

            /*
                setupNotifications:

                In this method, you associate each of your game notifications with your local method to handle it.

                Note: game notification names correspond to "notifyAllPlayers" and "notifyPlayer" calls in
                      your sagrada.game.php file.

            */
            setupNotifications: function () {
                console.log('notifications subscriptions setup');

                // TODO: here, associate your game notifications with local methods

                // Example 1: standard notification handling
                // dojo.subscribe( 'cardPlayed', this, "notif_cardPlayed" );
                dojo.subscribe('patternSelected', this, "notif_selectPattern");
                dojo.subscribe('playerTurn', this, "notif_playerTurn");
                dojo.subscribe('dieDrafted', this, "notif_dieDrafted");
                dojo.subscribe('updateDraftPool', this, "notif_updateDraftPool");

                // Example 2: standard notification handling + tell the user interface to wait
                //            during 3 seconds after calling the method in order to let the players
                //            see what is happening in the game.
                // dojo.subscribe( 'cardPlayed', this, "notif_cardPlayed" );
                // this.notifqueue.setSynchronous( 'cardPlayed', 3000 );
                //
            },

            // TODO: from this point and below, you can write your game notifications handling methods

            /*
            Example:

            notif_cardPlayed: function( notif )
            {
                console.log( 'notif_cardPlayed' );
                console.log( notif );

                // Note: notif.args contains the arguments specified during you "notifyAllPlayers" / "notifyPlayer" PHP call

                // TODO: play the card in the user interface.
            },   */

            notif_selectPattern: function (notif) {
                console.log('notif_selectPattern');
                console.log(notif);

                this.updateBoards(notif.args.boards)

                // Note: notif.args contains the arguments specified during you "notifyAllPlayers" / "notifyPlayer" PHP call

                // TODO: play the card in the user interface.
            },

            notif_playerTurn: function (notif) {
                // console.log('notif_playerTurn');
                // console.log(notif);
                // this.updateDraftPool(notif.args.draftPool);
                // this.updateBoards(notif.args.boards);

                // Note: notif.args contains the arguments specified during you "notifyAllPlayers" / "notifyPlayer" PHP call

                // TODO: play the card in the user interface.
            },

            notif_dieDrafted: function (notif) {
                let die = this.getDieFromDraftPool(notif.args.draftPoolId);
                this.addDieToBoard(notif.args.draftPoolId, notif.args.x, notif.args.y, die.color.char, die.value, notif.args.playerId);

                dojo.destroy("die_" + die.draftPoolId);

                // this.updateDraftPool(notif.args.draftPool);
                // this.updateBoards(notif.args.boards);
                // TODO: update draftpool here

            },

            notif_updateDraftPool: function (notif) {
                console.log('notif_updateDraftPool', notif);
                this.updateDraftPool(notif.args.draftPool);
            }
        });
    });
