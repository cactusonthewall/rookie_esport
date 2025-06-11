<?php
require_once("class/achievement.php");
require_once("class/data.php");

$achievement = new Achievement();
$arrKolom = array('idachievement' => $_GET['idachievement']);
$achievement -> deleteAchievement($arrKolom);

header("Location: achievement.php");
?>
