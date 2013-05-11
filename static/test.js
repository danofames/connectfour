connectfour = {
    interval : null
    , turns : {
        0 : [1, 2]
        , 1 : [1, 5]
    }
    , run_test : function(game) {
        var game_data = game.data;
        var current_turn = 0
        var request_data = {
            game_id : game_data.game_id
            , col : $(this).data('connect-four-col')
        }

        connectfour.interval = setInterval(function() {
            if (current_turn >= connectfour.turns[game_data.player_id].length) {
                clearInterval(connectfour.interval)
                return;
            }
            $.post('/game/move.php', {
                    game_id : game_data.game_id
                    , col : connectfour.turns[game_data.player_id][current_turn]
                },
                function(resp) {
                    if (resp.status === 1) {
                        current_turn ++;
                    }
                    if (resp.game !== undefined) {
                        game.update_game_board(resp.game)
                   }
                }
            , "json")
        }, 500)
    }
}