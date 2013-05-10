<?php
$height = 6;
$width = 7;
$height1 = $height + 1;
$height2 = $height + 2;
$size = $height * $width;
$size1 = $height1 * $width;
$all1 = (1 << $size1) - 1; // 1111111111111111111111111111111111111111111111111
$col1 = (1 << $height1) - 1;  // 1111111
$bottom = $all1 / $col1;  // 1000000100000010000001000000100000010000001
$top = $bottom << $height;

$max_row_count = 7;
$game_template = (object) array(
    'id' => null,
    'players' => array(),
    'turns' => array(),
    'board' => array(0,0),
    'cols' => array()
);

for ($i=0; $i<$max_row_count; $i++) {
    array_push($game_template->cols, $i * $height1);
}

function is_valid_move($game, $player_id, $col) {
    global $top;
    return (($game->board[$player_id] | (1 << $game->cols[$col])) & $top) == 0;
}

function make_move($game, $player_id, $col) {
    if (is_valid_move($game, $player_id, $col)) {
        $game->board[$player_id] |= (1 << ($game->cols[$col]++));
        return true;
    }
    return false;
}
function has_won($game, $player_id) {
    global $height, $height1, $height2;
    $board = $game->board[$player_id];

    // check horizontal
    $y = $board & ($board >> $height1);
    if (($y & ($y >> 2 * $height1)) != 0) {
        return true;
    }

    // check diagonal
    $y = $board & ($board >> $height);
    if (($y & ($y >> 2 * $height)) != 0) {
        return true;
    }

    // check diagonal
    $y = $board & ($board >> $height2);
    if (($y & ($y >> 2 * $height2)) != 0) {
        return true;
    }

    // check vertical
    $y = $game->board[$player_id] & ($game->board[$player_id] >> 1);
    return ($y & ($y >> 2)) != 0;
}
?>