<?php
require_once("class/achievement.php");
require_once("class/data.php");

$achievement = new Achievement();

$arrKolom = array(
    'idteam' => $_POST['idteam'],
    'name_achievement' => $_POST['name_achievement'],
    'date_achievement' => $_POST['date_achievement'],
    'description_achievement' => $_POST['description_achievement']
);

$last_id = $achievement->addAchievement($arrKolom);
$hasil = $last_id ? 1 : 0;

header("location: achievement.php?hasil=" . $hasil);
?>
