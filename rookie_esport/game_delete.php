<?php
require_once("class/game.php");
require_once("class/data.php");

$game = new Game();
$arrKolom = array('idgame' => $_GET['idgame']);
$game ->deleteGame($arrKolom);

header("Location: game.php");
?>
