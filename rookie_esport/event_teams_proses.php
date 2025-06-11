<?php
require_once("class/team.php");
require_once("class/data.php");

$team = new Team();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit']) && $_POST['submit'] == 'assign') {
    $idevent = $_POST['idevent'];
    $idteam = $_POST['idteam'];
    
    $team->addTeamToEvent(['idteam' => $idteam, 'idevent' => $idevent]);
}

header("location: team_event.php?hasil=" . $hasil);
?>
