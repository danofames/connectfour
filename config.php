<?php
$config = json_decode(file_get_contents(__DIR__ . '/config.json'));
$game_template = array(
    'id' => null,
    'players' => array(),
    'turns' => array(),
    'board' => array(
        array(),
        array(),
        array(),
        array(),
        array()
    )
);
$max_row_count = 6;
?>