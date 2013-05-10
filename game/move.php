<?php
require_once '../config.php';

if (isset($_POST['game_id'])) {
    $game_id = $_POST['game_id'];
}
else {
    header('Content-type: application/json');
    echo json_encode(array(
        'status' => 0,
        'message' => 'need game id'
    ));
    exit();
}

require_once 'setup.php';

if (!file_exists($game_file_path)) {
    header('Content-type: application/json');
    echo json_encode(array(
        'status' => 0,
        'message' => 'not an active game'
    ));
    exit();
}

$game = json_decode(file_get_contents($game_file_path));

if (!in_array($player_id, $game->players)) {
    header('Content-type: application/json');
    echo json_encode(array(
        'status' => 0,
        'message' => 'not a valid player id'
    ));
    exit();
}

$col = $_POST['col'];

if (count($game->turns) > 0) {
    if ($game->turns[count($game->turns)-1][0] == $player_id) {
        header('Content-type: application/json');
        echo json_encode(array(
            'status' => 0,
            'message' => 'not your turn'
        ));
        exit();
    }
}

if (!isset($game->cols[$col])) {
    header('Content-type: application/json');
    echo json_encode(array(
        'status' => 0,
        'message' => 'not a valid row'
    ));
    exit();
}

if (!make_move($game, $player_id, $col)) {
    header('Content-type: application/json');
    echo json_encode(array(
        'status' => 0,
        'message' => 'not a valid move'
    ));
    exit();
}

if (has_won($game, $player_id)) {
    // do something
}

array_push($game->turns, array(
    intval($player_id),
    intval($col)
));

if (count($game->turns) > 0) {
    $game->last_move_player = $game->turns[count($game->turns)-1][0];
}
else {
    $game->last_move_player = null;
}

save_game_file($game_file_tmp_path, $game_file_path, $game);

header('Content-type: application/json');
echo json_encode($game);
?>
