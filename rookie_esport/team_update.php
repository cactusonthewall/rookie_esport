<?php
require_once("class/team.php");
require_once("class/data.php");

$team = new Team();
$idteam = $_GET['idteam'];
$uploadMessage = '';  

if (isset($idteam)) {
    $arrKolom = array('idteam' => $idteam);
    $dataTeam = $team->selectTeam($arrKolom);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name_team = $_POST['name_team'];
    $idgame = $_POST['idgame'];
    $idteam = $_POST['idteam'];

    $arrKolomUpdate = array(
        'name_team' => $name_team,
        'idgame' => $idgame,
        'idteam' => $idteam
    );
    $team->updateTeam($arrKolomUpdate);

    if (isset($_FILES['team_image']) && $_FILES['team_image']['error'] == 0) {
        $target_dir = "uploads/team_images/";
        $target_file = $target_dir . $idteam . ".jpg";  
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if ($imageFileType == "jpg" || $imageFileType == "jpeg") {
            $old_image_path = $target_dir . $idteam . ".jpg";
            if (file_exists($old_image_path)) {
                unlink($old_image_path); 
            }

            if (move_uploaded_file($_FILES["team_image"]["tmp_name"], $target_file)) {
                $uploadMessage = "Update photo berhasil"; 
            } else {
                $uploadMessage = "Sorry, there was an error uploading your file."; 
            }
        } else {
            $uploadMessage = "Sorry, only JPG or JPEG files are allowed.";  
        }
    }
}

if (isset($dataTeam)) {
    $name_team = htmlspecialchars($dataTeam['name']);
    $idgame = $dataTeam['idgame'];
    $game_name = htmlspecialchars($dataTeam['game_name']);
    $idteam = $dataTeam['idteam'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Team Update</title>
    <link rel="stylesheet" type="text/css" href="css/game_up.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="content-container">
        <div class="form-section">
            <div class="button-container">
                <a href="team.php" class="btn back-btn">Back</a>
            </div>

            <?php if ($uploadMessage != ''): ?>
                <p class="notification-message"><?php echo $uploadMessage; ?></p>
            <?php endif; ?>

            <h2>Update Team</h2>

            <form method="post" action="" enctype="multipart/form-data" class="form-container">
                <input type="hidden" name="idteam" value="<?php echo htmlspecialchars($dataTeam['idteam']); ?>">

                <div class="form-group">
                    <div class="input-group">
                        <label for="name_team">Team Name</label>
                        <input type="text" id="name_team" name="name_team" value="<?php echo htmlspecialchars($name_team); ?>" required class="form-input">
                    </div>

                    <div class="input-group">
                        <label for="idgame">Select Game</label>
                        <select name="idgame" required class="form-input" id="idgame">
                            <?php 
                                $team->selectGameUpdate($dataTeam['idgame']);
                            ?>
                        </select>
                    </div>

                    <div class="input-group">
                        <label for="team_image">Upload New Team Image (JPG only)</label>
                        <input type="file" name="team_image" accept="image/*" class="form-input" id="team_image">
                    </div>
                </div>

                <button type="submit" name="submit" value="update" class="submit-btn">Update Team</button>
            </form>
        </div>
    </div>
</body>
</html>
