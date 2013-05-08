<?php

$game_file_path = $config->game_dir.'/'.$game_id.'.json';
$game_file_tmp_path = $config->game_tmp_dir.'/'.$game_id.'.json';

$player_id_cookie_name = $game_id . '-player_id';

if (isset($_COOKIE[$player_id_cookie_name])) {
    $player_id = $_COOKIE[$player_id_cookie_name];
}
else {
    $player_id = null;
}

?>