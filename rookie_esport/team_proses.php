<?php
require_once("class/team.php");
require_once("class/data.php");

$team = new Team();

if (isset($_POST['submit'])) {
    $name_team = $_POST['name_team'];
    $idgame = $_POST['idgame'];

    $arrKolom = array(
        'idgame' => $_POST['idgame'],
        'name_team' => $_POST['name_team']
    );
    $last_id = $team->addTeam($arrKolom);
    
    if (isset($_FILES['team_image']) && $_FILES['team_image']['error'] == 0) {
        $target_dir = "uploads/team_images/";
        $target_file = $target_dir . $last_id . ".jpg"; 
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $valid_extensions = ['jpg', 'jpeg'];
        if (in_array($imageFileType, $valid_extensions)) {
            if (move_uploaded_file($_FILES["team_image"]["tmp_name"], $target_file)) {
                echo "The file " . basename($_FILES["team_image"]["name"]) . " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        } else {
            echo "Sorry, only JPG, JPEG files are allowed.";
        }
    } else {
        echo "No image uploaded.";
    }

    $hasil = $last_id ? 1 : 0;
}
header("location: team.php?hasil=" . $hasil);
?>
