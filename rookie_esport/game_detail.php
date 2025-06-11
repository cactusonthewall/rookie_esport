<?php 
require_once("class/game.php");
require_once("class/team.php");
require_once("class/event.php");

$game = new Game();  
$team = new Team();
$event = new Event();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Details</title>
    <link rel="stylesheet" type="text/css" href="css/game_dett.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
<div class="button-container">
                <a href="home.php" class="btn back-btn">Back</a>
            </div>
<div class="table-wrapper">
        <?php 
        if (isset($_GET['idgame'])) {
            $idgame = (int)$_GET['idgame'];
            $game_details = $game->selectGame(['idgame' => $idgame]);

            if ($game_details) {
                echo "<h1>" . htmlspecialchars($game_details['name']) . "</h1>";
                echo "<p>" . htmlspecialchars($game_details['description']) . "</p>";

                $teams = $team->getTeam("", null, null);
                if ($teams->num_rows > 0) {
                    echo "<table>";
                    echo "<tr><th>Team Name</th><th>Events</th></tr>";
                    while ($row = $teams->fetch_assoc()) {
                        echo "<tr>";

                        echo "<td><span class='team-name'>" . htmlspecialchars($row['name']) . "</span></td>";

                        $team_events = $event->getEventTeams($row['idteam']);
                        echo "<td><ul>";
                        if ($team_events->num_rows > 0) {
                            while ($event_row = $team_events->fetch_assoc()) {
                                echo "<li>";
                                echo "<table class='event-table'>";
                                echo "<tr><td class='event-name'>" . htmlspecialchars($event_row['name']) . "</td><td class='event-date'>" . htmlspecialchars($event_row['date']) . "</td></tr>";
                                echo "</table>";
                                echo "</li>";
                            }
                        } else {
                            echo "<li>No events found for this team.</li>";
                        }
                        echo "</ul></td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p>No teams found for this game.</p>";
                }
            } else {
                echo "<p>Game not found.</p>";
            }
        } else {
            echo "<p>Game ID is missing.</p>";
        }
        ?>
    </div>
</body>
</html>