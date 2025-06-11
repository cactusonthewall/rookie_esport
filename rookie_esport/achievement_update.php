<?php
require_once("class/achievement.php");
require_once("class/data.php");

$achievement = new Achievement();

if (isset($_GET['idachievement'])) {
    $arrKolom = array('idachievement' => $_GET['idachievement']);
    $dataAchievement = $achievement->selectAchievement($arrKolom);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $arrKolom = array(
        'name_achievement' => $_POST['name_achievement'],
        'date_achievement' => $_POST['date_achievement'],
        'description_achievement' => $_POST['description_achievement'],
        'idteam' => $_POST['idteam'],
        'idachievement' => $_POST['idachievement']
    );
    $achievement->updateAchievement($arrKolom);
}

if (isset($dataAchievement)) {
    $name_achievement = htmlspecialchars($dataAchievement['name']);
    $date_achievement = htmlspecialchars($dataAchievement['date']);
    $description_achievement = htmlspecialchars($dataAchievement['description']);
    $idteam = $dataAchievement['idteam'];
    $idachievement = $dataAchievement['idachievement'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Update Achievement</title>
    <link rel="stylesheet" type="text/css" href="css/game_up.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="content-container">
        <div class="form-section">
            <div class="button-container">
                <a href="achievement.php" class="btn back-btn">Back</a>
            </div>

            <h2>Update Achievement</h2>

            <form method="post" action="" class="form-container">
                <input type="hidden" name="idachievement" value="<?php echo htmlspecialchars($idachievement); ?>">

                <div class="form-group">
                    <div class="input-group">
                        <label for="name_achievement">Achievement Name</label>
                        <input type="text" id="name_achievement" name="name_achievement" value="<?php echo htmlspecialchars($name_achievement); ?>" required>
                    </div>

                    <div class="input-group">
                        <label for="date_achievement">Achievement Date</label>
                        <input type="date" id="date_achievement" name="date_achievement" value="<?php echo htmlspecialchars($date_achievement); ?>" required>
                    </div>

                    <div class="input-group">
                        <label for="description_achievement">Achievement Description</label>
                        <textarea id="description_achievement" name="description_achievement" required><?php echo htmlspecialchars($description_achievement); ?></textarea>
                    </div>

                    <div class="input-group">
                        <label for="idteam">Select Team</label>
                        <select id="idteam" name="idteam" required>
                            <?php 
                                $achievement->selectTeamUpdate($idteam);
                            ?>
                        </select>
                    </div>
                </div>

                <button type="submit" class="submit-btn">Update Achievement</button>
            </form>
        </div>
    </div>
</body>
</html>
