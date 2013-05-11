<?php

if (isset($game_id)) {
    $game_file_path = $config->game_dir.'/'.$game_id.'.json';
    $game_file_tmp_path = $config->game_tmp_dir.'/'.$game_id.'.json';
}

$player_id_cookie_name = $game_id . '-player_id';

if (isset($_REQUEST['player_id'])) {
    $player_id = $_REQUEST['player_id'];
}
else {
    $player_id = null;
}


function write_response_and_end($message) {
    header('Content-type: application/json');
    echo json_encode($message) . "\n";
    exit();
}

function save_game_file($game_file_tmp_path, $game_file_path, $game) {
    if (file_put_contents($game_file_tmp_path, json_encode($game))) {
        try {
            rename($game_file_tmp_path, $game_file_path);
        }
        catch (Exception $e) {
            file_put_contents('php://stderr', $e->getMessage());
        }
    }
}

?>
