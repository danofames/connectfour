<?php
require_once '../config.php';

if (isset($_POST['game_id'])) {
    $game_id = $_POST['game_id'];
}
else {
    header('Content-type: application/json');
    echo json_encode(array(
        'status' => -1
    ));
    exit();
}

require_once 'setup.php';

if (!file_exists($game_file_path)) {
    header('Content-type: application/json');
    echo json_encode(array(
        'status' => -1,
        'message' => 'not an active game'
    ));
    exit();
}

$game = json_decode(file_get_contents($game_file_path));

if (!in_array($player_id, $game->players)) {
    header('Content-type: application/json');
    echo json_encode(array(
        'status' => -1,
        'message' => 'not a valid player id'
    ));
    exit();
}

$row = $_POST['row'];

if (count($game->turns) > 0) {
    if ($game->turns[count($game->turns)-1][0] == $player_id) {
        header('Content-type: application/json');
        echo json_encode(array(
            'status' => -1,
            'message' => 'not your turn yo'
        ));
        exit();
    }
}

if (count($game->board[$row]) >= $max_row_count) {
    header('Content-type: application/json');
    echo json_encode(array(
        'status' => -1,
        'message' => 'that row is full'
    ));
    exit();
}

if (!isset($game->board[$row])) {
    header('Content-type: application/json');
    echo json_encode(array(
        'status' => -1,
        'message' => 'not a valid row'
    ));
    exit();
}

array_push($game->board[$row], intval($player_id));
array_push($game->turns, array(
    intval($player_id),
    intval($row)
));

if (file_put_contents($game_file_tmp_path, json_encode($game))) {
    try {
        rename($game_file_tmp_path, $game_file_path);
    }
    catch (Exception $e) {
        file_put_contents('php://stderr', $e->getMessage());
    }
}

header('Content-type: application/json');
echo json_encode($game);
?>