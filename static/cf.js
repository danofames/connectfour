(function() {
    var game = {
        data : {}
        , interval : null
        , update_game_board : function(game_state) {
            var board_cols = 7, board_pos;
            if (game_state.turns.length <= 0) {
                return;
            }
            if (game_state.winner !== undefined) {
                $('#connect-four-board').removeClass('connect-four-state-this-turn')
                $('#connect-four-board').removeClass('connect-four-state-not-this-turn')
                $('#connect-four-board').addClass('connect-four-state-game-over')
                $('#connect-four-board').addClass(
                    (parseInt(game_state.winner) === game.data.player_id)
                        ? 'connect-four-state-this-win'
                        : 'connect-four-state-not-this-win'
                )
                clearInterval(game.interval)
            }
            else if (game_state.last_move_player !== game.data.player_id) {
                $('#connect-four-board').addClass('connect-four-state-this-turn')
                $('#connect-four-board').removeClass('connect-four-state-not-this-turn')
            }
            else {
                $('#connect-four-board').removeClass('connect-four-state-this-turn')
                $('#connect-four-board').addClass('connect-four-state-not-this-turn')
            }
            for (var p = 0; p<game_state.players.length; p++) {
                for (var i = game_state.displayboard[p].length;i>=0;i--) {
                    if (parseInt(game_state.displayboard[p].charAt(i-1))) {
                        board_pos = game_state.displayboard[p].length-i
                        $('#connect-four-board tr:nth-child(' + Math.floor(board_pos / board_cols + 1) + ') td:nth-child(' + (board_pos % board_cols + 1) + ')')
                            .addClass('connect-four-cell-state-occupied')
                            .addClass('connect-four-cell-state-player-' + p)
                    }
                }
            }
        }
    }
    , request_data = null;

    if (window.location.hash) {
        request_data = {
            game_id : window.location.hash.replace('#','')
        }
    }

    $.getJSON('/game/', request_data, function(resp) {
        if (resp.game === undefined || resp.game.id === undefined) {
            return
        }

        window.history.pushState(resp, 'initial', '#' + resp.game.id)
        game.data.game_id = resp.game.id
        game.data.player_id = resp.game.player_id

        game.update_game_board(resp.game)
    })

    $('#connect-four-board').on('click', '.connect-four-col', function(ev) {
        var request_data = {
            game_id : game.data.game_id
            , player_id : game.data.player_id
            , col : $(this).data('connect-four-col')
        }
        $.post('/game/move.php', request_data, function(resp) {
            game.update_game_board(resp.game)
        }, 'json')
    })

    $('#btn-update-board').on('click', function(ev) {
        ev.preventDefault()
        $.getJSON('/game/', {
                game_id : game.data.game_id
                , player_id : game.data.player_id
            }, function(resp) {
            game.update_game_board(resp.game)
        })
    })

    game.interval = setInterval(function() {
        $.getJSON('/game/', {
                game_id : game.data.game_id
                , player_id : game.data.player_id
            }, function(resp) {
            game.update_game_board(resp.game)
        })
    }, 500)
})()
