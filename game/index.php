<?php
require_once '../config.php';

if (isset($_GET['game_id'])) {
    $game_id = $_GET['game_id'];
}
else {
    $game_id = substr(str_shuffle(MD5(microtime())), 0, 5);
}

$game_file_path = $config->game_dir.'/'.$game_id.'.json';
$game_file_tmp_path = $config->game_tmp_dir.'/'.$game_id.'.json';

$player_id_cookie_name = $game_id . '-player_id';

if (isset($_COOKIE[$player_id_cookie_name])) {
    $player_id = $_COOKIE[$player_id_cookie_name];
}
else {
    $player_id = null;
}

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

if (file_put_contents($game_file_tmp_path, json_encode($game))) {
    try {
        rename($game_file_tmp_path, $game_file_path);
    }
    catch (Exception $e) {
        file_put_contents('php://stderr', $e->getMessage());
    }
}

header('Content-type: application/json');
setcookie($player_id_cookie_name, $player_id, time()+3600, '/');
$game->last_move_player = $game->turns[count($game->turns)-1][0];
echo json_encode($game);
?>
