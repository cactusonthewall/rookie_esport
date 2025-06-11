<?php
require_once("class/event.php");
require_once("class/team.php");
require_once("class/data.php");

$event = new Event();
$team = new Team();

if (isset($_GET['idteam']) && isset($_GET['idevent'])) {
    $idteam = $_GET['idteam'];
    $idevent = $_GET['idevent'];
    
    if ($event->removeTeamFromEvent($idteam, $idevent)) {
        header("Location: team_event.php?idteam=" . $idteam . "&deleted=1");
        exit();
    } else {
        $error = "Failed to remove the team from the event.";
    }
} else {
    header("Location: team.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Delete Team Event</title>
</head>
<body>
    <h2>Delete Team Event</h2>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <p>An error occurred. Please try again or contact the administrator.</p>
    <a href="team_event.php?idteam=<?php echo $idteam; ?>">Back to Team Events</a>
</body>
</html>