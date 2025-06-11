<?php
require_once("class/event.php");
require_once("class/data.php");

$event = new Event();
$idevent = $_GET['idevent'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $arrKolom = array(
        'name_event' => $_POST['name_event'], 
        'date_event' => $_POST['date_event'], 
        'description_event' => $_POST['description_event'], 
        'idevent' => $_POST['idevent']);
    $event->updateEvent($arrKolom);
}

if (isset($_GET['idevent'])) {
    $arrKolom = array('idevent' => $_GET['idevent']);
    $dataEvent = $event->selectEvent($arrKolom);
}

if (isset($dataEvent)) {
    $name_event = htmlspecialchars($dataEvent['name']);
    $date_event = htmlspecialchars($dataEvent['date']);
    $description_event = htmlspecialchars($dataEvent['description']);
    $idevent = $dataEvent['idevent'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Update Event</title>
    <link rel="stylesheet" type="text/css" href="css/game_up.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="content-container">
        <div class="form-section">
            <div class="button-container">
                <a href="event.php" class="btn back-btn">Back</a>
            </div>

            <h2>Update Event</h2>

            <form method="post" action="" class="form-container">
                <input type="hidden" name="idevent" value="<?php echo htmlspecialchars($idevent); ?>">

                <div class="form-group">
                    <div class="input-group">
                        <label for="name_event">Event Name</label>
                        <input type="text" id="name_event" name="name_event" value="<?php echo htmlspecialchars($name_event); ?>" required>
                    </div>

                    <div class="input-group">
                        <label for="date_event">Event Date</label>
                        <input type="date" id="date_event" name="date_event" value="<?php echo htmlspecialchars($date_event); ?>" required>
                    </div>

                    <div class="input-group">
                        <label for="description_event">Event Description</label>
                        <textarea id="description_event" name="description_event" required><?php echo htmlspecialchars($description_event); ?></textarea>
                    </div>
                </div>

                <button type="submit" class="submit-btn">Update Event</button>
            </form>
        </div>
    </div>
</body>
</html>
