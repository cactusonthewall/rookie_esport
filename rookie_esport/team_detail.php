<?php 
require_once("class/team.php");
require_once("class/achievement.php");
require_once("class/event.php");
require_once("class/team_members.php");

// Inisialisasi Objek
$team = new Team();
$achievement = new Achievement(); 
$event = new Event();
$team_member = new TeamMember();
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Team Details</title>
   <link rel="stylesheet" type="text/css" href="css/team_det.css">
   <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
<div class="main-container">
    <div class="header">
        <a href="home.php" class="btn back-btn">Back</a>
        <?php 
        if (isset($_GET['idteam']) && is_numeric($_GET['idteam'])) {
            $idteam = (int)$_GET['idteam'];
            $team_details = $team->selectTeam(['idteam' => $idteam]);

            if ($team_details) {
                echo "<h1 class='team-title'>" . htmlspecialchars($team_details['name']) . "</h1>";
                echo "<p id='game-title'>" . htmlspecialchars($team_details['game_name']) . "</p>";
            } else {
                echo "<p>Team not found.</p>";
            }
        } else {
            echo "<p>Invalid or missing team ID.</p>";
        }
        ?>
    </div>
    <div class="content-section">
        <div class="section">
            <h2>Team Members</h2>
            <?php 
            $members = $team_member->getTeamMembers($idteam ?? 0);
            if (!empty($members)) {
                echo "<table class='data-table'>";
                echo "<tr><th>Name</th></tr>";
                foreach ($members as $member) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($member['fname'] . " " . $member['lname']) . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No team members found.</p>";
            }
            ?>
        </div>
        <div class="section">
            <h2>Achievements</h2>
            <?php 
            $team_achievements = $achievement->getAchievementTeams($idteam ?? 0);
            if (!empty($team_achievements)) {
                echo "<table class='data-table'>";
                echo "<tr><th>Achievement</th><th>Date</th><th>Description</th></tr>";
                foreach ($team_achievements as $ach) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($ach['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($ach['date']) . "</td>";
                    echo "<td>" . htmlspecialchars($ach['description']) . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No achievements found.</p>";
            }
            ?>
        </div>
        <div class="section">
            <h2>Upcoming Events</h2>
            <?php 
            $team_events = $event->displayUpcomingEvents($idteam ?? 0);
            if (!empty($team_events)) {
                echo "<table class='data-table'>";
                echo "<tr><th>Event Name</th><th>Date</th><th>Description</th></tr>";
                foreach ($team_events as $event_row) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($event_row['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($event_row['date']) . "</td>";
                    echo "<td>" . htmlspecialchars($event_row['description']) . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No upcoming events found.</p>";
            }
            ?>
        </div>
    </div>
</div>
</body>
</html>