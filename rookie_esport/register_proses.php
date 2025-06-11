<?php 
require_once("class/member.php");
require_once("class/data.php");

$member = new Member();

$arrKolom = array(
    'fname' => $_POST['fname'],
    'lname' => $_POST['lname'],
    'username' => $_POST['username'],
    'password' => $_POST['password'] 
);

$last_id = $member->addMember($arrKolom);
$hasil = $last_id ? 1 : 0;

header("location: login.php");
?>
