<?php 
session_start();
require_once("class/data.php");
require_once("class/team.php");
require_once("class/game.php");
require_once("class/event.php");
require_once("class/achievement.php");
require_once("class/proposal.php");

$team = new Team();
$totalTeams = $team->getTotalTeams();
$game = new Game();
$totalGames = $game->getTotalGames();
$event = new Event();
$totalEvents = $event->getTotalEvents();
$achievement = new Achievement();
$totalAchievements = $achievement->getTotalAchievements();
$proposal = new Proposal();
$totalProposals = $proposal->getTotalProposals();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/admindas.css">
</head>
<body>
<div class="main-content">
    <header class="main-header">
        <h1>Admin Dashboard</h1>
    </header>

    <section class="dashboard">
        <a href="team.php" class="dash-card">
            <h3><?php echo $totalTeams; ?></h3>
            <p>Total Teams</p>
        </a>
        <a href="game.php" class="dash-card">
            <h3><?php echo $totalGames; ?></h3>
            <p>Total Games</p>
        </a>
        <a href="event.php" class="dash-card">
            <h3><?php echo $totalEvents; ?></h3>
            <p>Events</p>
        </a>
        <a href="achievement.php" class="dash-card">
            <h3><?php echo $totalAchievements; ?></h3>
            <p>Achievements</p>
        </a>
        <a href="admin_proposal.php" class="dash-card">
            <h3><?php echo $totalProposals; ?></h3>
            <p>Proposal</p>
        </a>
    </section>
    <div class="logout-container">
        <form action="logout.php" method="post">
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>
</div>
</body>
</html>