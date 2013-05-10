<?php
require_once '../config.php';

if (isset($_GET['game_id'])) {
    $game_id = $_GET['game_id'];
}
else {
    $game_id = substr(str_shuffle(MD5(microtime())), 0, 5);
}

require_once 'setup.php';

if (file_exists($game_file_path)) {
    $game = json_decode(file_get_contents($game_file_path));
    if (!$player_id) {
        if (count($game->players) >= 2) {
            header('Content-type: application/json');
            echo json_encode(array(
                'status' => -1,
                'message' => 'game has all players'
            ));
            exit();
        }
        else {
            $player_id = count($game->players) + 1;
            array_push($game->players, $player_id);
        }
    }
    else if (!in_array($player_id, $game->players)) {
        header('Content-type: application/json');
        echo json_encode(array(
            'status' => -1,
            'message' => 'not a valid player id'
        ));
        exit();
    }

}
else {
    $game = (object) $game_template;
    $game->id = $game_id;
    $player_id = 1;
    array_push($game->players, $player_id);
}

save_game_file($game_file_tmp_path, $game_file_path, $game);

if (count($game->turns) > 0) {
    $game->last_move_player = $game->turns[count($game->turns)-1][0];
}
else {
    $game->last_move_player = null;
}

header('Content-type: application/json');
setcookie($player_id_cookie_name, $player_id, strtotime('+10 days'), '/');
echo json_encode($game);
?>
