<?php
require_once '../config.php';

if (isset($_GET['game_id'])) {
    $game_id = $_GET['game_id'];
}
else {
    $game_id = substr(str_shuffle(MD5(microtime())), 0, 5);
}

require_once 'setup.php';
require_once 'game.php';

if (file_exists($game_file_path)) {
    $game = json_decode(file_get_contents($game_file_path));
    if (!isset($player_id)) {
        if (count($game->players) >= 2) {
            write_response_and_end(array(
                'status' => 0,
                'message' => 'game has all players'
            ));
        }
        else {
            $player_id = count($game->players);
            array_push($game->players, $player_id);
        }
    }
    else if (!in_array($player_id, $game->players)) {
        write_response_and_end(array(
            'status' => 0,
            'message' => 'not a valid player id'
        ));
    }

}
else {
    $game = (object) $game_template;
    $game->id = $game_id;
    $player_id = 0;
    array_push($game->players, $player_id);
}

save_game_file($game_file_tmp_path, $game_file_path, $game);

if (count($game->turns) > 0) {
    $game->last_move_player = $game->turns[count($game->turns)-1][0];
}
else {
    $game->last_move_player = null;
}

$game->player_id = $player_id;

write_response_and_end(array(
    'status' => 1,
    'game' => $game
));
?>
