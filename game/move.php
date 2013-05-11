<?php
require_once '../config.php';

if (isset($_POST['game_id'])) {
    $game_id = $_POST['game_id'];
}
else {
    write_response_and_end(array(
        'status' => 0,
        'message' => 'need game id'
    ));
}
$col = $_POST['col'];

require_once 'setup.php';
require_once 'game.php';

if (!file_exists($game_file_path)) {
    write_response_and_end(array(
        'status' => 0,
        'message' => 'not an active game'
    ));
}

$game = json_decode(file_get_contents($game_file_path));

if (isset($game->winner)) {
    write_response_and_end(array(
        'status' => 0,
        'message' => 'game has a winner'
    ));
}

if (!in_array($player_id, $game->players)) {
    write_response_and_end(array(
        'status' => 0,
        'message' => 'not a valid player id'
    ));
}

if (count($game->turns) > 0) {
    if ($game->turns[count($game->turns)-1][0] == $player_id) {
        write_response_and_end(array(
            'status' => 0,
            'message' => 'not your turn'
        ));
    }
}

if (!isset($game->cols[$col])) {
    write_response_and_end(array(
        'status' => 0,
        'message' => 'not a valid row'
    ));
}

if (!make_move($game, $player_id, $col)) {
    write_response_and_end(array(
        'status' => 0,
        'message' => 'not a valid move'
    ));
}

array_push($game->turns, array(
    intval($player_id),
    intval($col)
));

if (has_won($game, $player_id)) {
    $game->winner = $player_id;
}

if (count($game->turns) > 0) {
    $game->last_move_player = $game->turns[count($game->turns)-1][0];
}
else {
    $game->last_move_player = null;
}

$game->displayboard = display_board($game);
$game->player_id = $player_id;

save_game_file($game_file_tmp_path, $game_file_path, $game);

write_response_and_end(array(
    'status' => 1,
    'game' => $game
));

?>
