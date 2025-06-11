<?php
require_once("class/event.php");
require_once("class/data.php");

$event = new Event();

$arrKolom = array(
    'name_event' => $_POST['name_event'],
    'date_event' => $_POST['date_event'],
    'description_event' => $_POST['description_event']
);

$last_id = $event->addEvent($arrKolom);
$hasil = $last_id ? 1 : 0;

header("location: event.php?hasil=" . $hasil);
?>
