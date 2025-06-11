<?php
require_once("class/event.php");
require_once("class/data.php");

$event = new Event();

if (isset($_GET['idteam']) && isset($_GET['idevent'])) {
    $idteam = $_GET['idteam'];
    $idevent = $_GET['idevent'];
    $team_name = $event->getTeamName($idteam);
    $event_details = $event->getEventDetails($idevent);
    if (!$team_name || !$event_details) {
        header("Location: team.php");
        exit();
    }
} else {
    header("Location: team.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $new_idevent = $_POST['new_idevent'];
    
    $result = $event->updateTeamEvent($idteam, $idevent, $new_idevent);
    
    if ($result === true) {
        header("Location: team_event.php?idteam=" . $idteam . "&updated=1");
        exit();
    } elseif ($result === "duplicate") {
        $error = "This team is already registered for this event";
    } else {
        $error = "Failed to update the event";
    }
}

$all_events = $event->getAllEvents();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Update Team Event</title>
    <link rel="stylesheet" type="text/css" href="css/team_event_up.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="content-container">
        <div class="form-section">
            <div class="button-container">
                <a href="team_event.php?idteam=<?php echo $idteam; ?>" class="btn back-btn">Back</a>
            </div>

            <h2>Update Event for <?php echo htmlspecialchars($team_name); ?></h2>
            
            <?php if (isset($error)): ?>
                <div class="error-message">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="post" action="" class="form-container">
                <div class="form-group">
                    <p class="current-event"><?php echo htmlspecialchars($event_details['name']); ?></p>
                    <div class="input-group">
                        <label for="new_idevent">Select New Event:</label>
                        <select name="new_idevent" id="new_idevent" required>
                            <?php while ($row = $all_events->fetch_assoc()): ?>
                                <option value="<?php echo $row['idevent']; ?>" 
                                    <?php echo ($row['idevent'] == $idevent) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($row['name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                <button type="submit" name="submit" class="submit-btn">Update Event</button>
            </form>
        </div>
    </div>
</body>
</html>