<?php
require_once '../config.php';
require_once 'game.php';

header('Content-type: application/json');

$player1 = 0;
$player2 = 1;

function test_vertical() {
    global $game_template, $player1, $player2;
    $game = (object) $game_template;

    for ($i = 0; $i < 14; $i++) {
        $col = $i % 2;
        if ($col == 0) {
            $player = $player1;
        }
        else {
            $player = $player2;
        }

        if (!make_move($game, $player, $col)) {
            // echo 'row full' . '\n';
        }

        if (has_won($game, $player)) {
            printf("%s won the game\n", $player);
            break;
        }
    }
    echo json_encode($game) . "\n";
}

function test_horizontal() {
    global $game_template, $player1, $player2;
    $game = (object) $game_template;

    for ($i = 0; $i < 6; $i++) {

        if (!make_move($game, $player1, $i)) {
            // echo 'row full' . '\n';
        }

        if (has_won($game, $player1)) {
            printf("%s won the game\n", $player1);
            break;
        }
    }
    echo json_encode($game) . "\n";
}

function test_diagnoal() {
    global $game_template, $player1, $player2;
    $game = (object) $game_template;

    make_move($game, $player1, 0);
    make_move($game, $player2, 1);
    make_move($game, $player1, 2);
    make_move($game, $player2, 2);
    make_move($game, $player1, 1);
    make_move($game, $player2, 3);
    make_move($game, $player1, 2);
    make_move($game, $player2, 3);
    make_move($game, $player1, 4);
    make_move($game, $player2, 3);
    make_move($game, $player1, 3);

    if (has_won($game, $player1)) {
        printf("%s won the game\n", $player1);
    }
    echo json_encode($game) . "\n";
}

function test_diagonal_reversed() {
    global $game_template, $player1, $player2;
    $game = (object) $game_template;

    make_move($game, $player1, 5);
    make_move($game, $player2, 4);
    make_move($game, $player1, 3);
    make_move($game, $player2, 3);
    make_move($game, $player1, 4);
    make_move($game, $player2, 2);
    make_move($game, $player1, 3);
    make_move($game, $player2, 2);
    make_move($game, $player1, 1);
    make_move($game, $player2, 2);
    make_move($game, $player1, 2);

    if (has_won($game, $player1)) {
        printf("%s won the game\n", $player1);
    }
    echo json_encode($game) . "\n";
}

// test_vertical();
// test_horizontal();
// test_diagnoal();
test_diagonal_reversed();
?>