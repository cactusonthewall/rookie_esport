<?php 
require_once("class/team.php"); 
require_once("class/game.php");
require_once("class/data.php");

$team = new Team();
$game = new Game();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home</title>
    <link rel="stylesheet" href="css/homee.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="button-container">
        <a href="index.php" class="btn back-btn">Back</a>
    </div>
    <div class="container">
        <div class="team-card-container">
            <?php 
            $result = $team->getTeam("");
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $team_name = htmlspecialchars($row['name']);
                    $game_name = htmlspecialchars($row['game_name']);
                    $team_image_path = "uploads/team_images/" . $row['idteam'] . ".jpg";

                    $idgame = isset($row['idgame']) ? $row['idgame'] : null;
            
                    echo "<div class='team-card'>";
                    echo "<h3 class='team-name'>" . $team_name . "</h3>";
                    echo "<p class='game-name'>" . $game_name . "</p>";
            
                    if (file_exists($team_image_path)) {
                        $team_image_path .= "?" . filemtime($team_image_path);
                        echo "<img src='" . htmlspecialchars($team_image_path) . "' alt='Team Image' class='team-image'>";
                    } else {
                        echo "<p>No image available</p>";
                    }
            
                    echo "<div class='team-btn-container'>";
                    echo "<a href='team_detail.php?idteam=" . $row['idteam'] . "' class='btn detail-btn'>Team</a>";
                    if ($idgame) {
                        echo "<a href='game_detail.php?idgame=" . $idgame . "' class='btn detail-btn'>Game</a>";
                    } else {
                        echo "<p>Game details unavailable</p>"; 
                    }
                    echo "</div>"; 
                    echo "</div>"; 
                }
            } else {
                echo "<p>No teams found.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>
