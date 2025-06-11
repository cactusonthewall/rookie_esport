<?php
require_once("class/event.php");
require_once("class/data.php");

if (isset($_GET['idteam']) && isset($_GET['idevent'])) {
    $idteam = $_GET['idteam'];
    $idevent = $_GET['idevent'];

    $query = "DELETE FROM event_teams WHERE idteam = ? AND idevent = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ii", $idteam, $idevent);

    if ($stmt->execute()) {
        header("Location: event_teams.php?idteam=".$idteam);
    } else {
        echo "Failed to delete event for team.";
    }
} else {
    echo "Invalid request.";
}
?>
