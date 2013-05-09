(function() {
    var game = {
        data : {}
        , update_game_board : function(game_state) {
            if (game_state.last_move_player !== game.data.player_id) {
                $('body').addClass('connect-four-state-this-turn')
                $('body').removeClass('connect-four-state-not-this-turn')
            }
            else {
                $('body').removeClass('connect-four-state-this-turn')
                $('body').addClass('connect-four-state-not-this-turn')
            }
            for (var i=0;i<game_state.board.length;i++) {
                for (var j=0;j<game_state.board[i].length;j++) {
                    $('#connect-four-board tr:nth-child(' + (i + 1) + ') td:nth-child(' + (j + 1) + ')')
                        .addClass('connect-four-cell-state-occupied')
                        .addClass('connect-four-cell-state-player-' + game_state.board[i][j])
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
        if (resp.id === undefined) {
            return
        }
        window.history.pushState(resp, 'initial', '#' + resp.id)
        game.data.game_id = resp.id
        var cookies = document.cookie.split(';')
        for(var i=0;i < cookies.length;i++) {
            if (cookies[i].indexOf(game.data.game_id) > -1) {
                game.data.player_id = parseInt(cookies[i].split('=')[1]);
            }
        }

        game.update_game_board(resp)
    })

    $('#connect-four-board').on('click', '.connect-four-row', function(ev) {
        var request_data = {
            game_id : game.data.game_id
            , row : $(this).data('connect-four-row')
        }
        $.post('/game/move.php', request_data)
    })

    setInterval(function() {
        $.getJSON('/game/', { game_id : game.data.game_id }, function(resp) {
            game.update_game_board(resp)
        })
    }, 1000)
})()
