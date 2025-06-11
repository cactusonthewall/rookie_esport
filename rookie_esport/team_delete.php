<?php
require_once("class/team.php");
require_once("class/data.php");

$team = new Team();
$arrKolom = array('idteam' => $_GET['idteam']);
$team -> deleteTeam($arrKolom);

header("Location: team.php");
?>
