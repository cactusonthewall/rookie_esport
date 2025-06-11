<?php 
session_start();
require_once("class/user.php");
require_once("class/proposal.php");

$user = new User();
$proposal = new Proposal();

$username = $_POST['username'];
$plainPwd = $_POST['password'];

if ($user->doLogin($username, $plainPwd)) {
    $userku = $user->getUser($username);
    
    $_SESSION['username'] = $username;
    $_SESSION['name'] = $userku["fname"] . " " . $userku["lname"]; 
    $_SESSION['profile'] = $userku["profile"];
    $_SESSION['idmember'] = $userku["idmember"];
    $_SESSION['idteam'] = $userku["idteam"]; 


    if ($_SESSION['profile'] === 'admin') {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        header("Location: member_dashboard.php");
    }
} else {
    header("Location: login.php?error=1");
}
exit();
?>