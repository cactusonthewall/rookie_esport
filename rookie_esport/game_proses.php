<?php
require_once("class/game.php");
require_once("class/data.php");

$game = new Game();

$arrKolom = array(
    'nama_game' => $_POST['name_game'],
    'description_game' => $_POST['description_game']
);

$last_id = $game->addGame($arrKolom);
$hasil = $last_id ? 1 : 0;

header("location: game.php?hasil=" . $hasil);
?>
