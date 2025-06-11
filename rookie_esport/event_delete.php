<?php
require_once("class/event.php");
require_once("class/data.php");

$event = new Event();
$arrKolom = array('idevent' => $_GET['idevent']);
$event -> deleteEvent($arrKolom);

header("Location: event.php");
?>
