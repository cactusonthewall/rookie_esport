<?php 
require_once("class/team.php");
require_once("class/event.php");
require_once("class/data.php");

$team = new Team();
$event = new Event();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Event Teams</title>
    <link rel="stylesheet" type="text/css" href="css/event_teams.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="content-container">
        <div class="form-section">
            <div class="button-container">
                <a href="team.php" class="btn back-btn">Back</a>
            </div>

            <form method="post" action="event_teams_proses.php" class="form-container">
                <p>
                    <label>Select Team</label>
                    <select name="idteam" required>
                        <?php 
                        $teams = $team->getTeam();
                        while ($row = $teams->fetch_assoc()) {
                            echo "<option value='" . $row['idteam'] . "'>" . htmlspecialchars($row['name']) . "</option>";
                        }
                        ?>
                    </select>
                </p>
                <p>
                    <label>Select Event</label>
                    <select name="idevent" required>
                        <?php 
                        $events = $event->getEvent();
                        while ($row = $events->fetch_assoc()) {
                            echo "<option value='" . $row['idevent'] . "'>" . htmlspecialchars($row['name']) . "</option>";
                        }
                        ?>
                    </select>
                </p>
                <p><button type="submit" name="submit" value="assign">Assign Team to Event</button></p>
            </form>
        </div> 
    </div>
</body>
</html>