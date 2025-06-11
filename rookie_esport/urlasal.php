<?php 
session_start();
if(!isset($_SESSION['username'])){
	$domain = $_SERVER['HTTP_HOST'];
	$path = $_SERVER['SCRIPT_NAME'];
	$queryString = $_SERVER['QUERY_STRING'];
	$url = "http://" . $domain . $path . "?" . $queryString;

	header("location: utama.php?url_asal=".$url);
}
 ?>