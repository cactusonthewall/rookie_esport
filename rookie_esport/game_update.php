<?php 
require_once("class/game.php");
require_once("class/data.php");

$game = new Game();
$idgame = $_GET['idgame'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $arrKolom = array(
        'name_game' => $_POST['name_game'], 
        'description_game' => $_POST['description_game'], 
        'idgame' => $_POST['idgame']);
    $game->updateGame($arrKolom);
};

if (isset($_GET['idgame'])) {
    $arrKolom = array('idgame' => $_GET['idgame']);
    $dataGame = $game->selectGame($arrKolom);
}

$game->selectGame($arrKolom);
if (isset($dataGame)) {
    $name_game = htmlspecialchars($dataGame['name']);
    $description_game = htmlspecialchars($dataGame['description']);
    $idgame = $dataGame['idgame'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Update Game</title>
    <link rel="stylesheet" type="text/css" href="css/game_up.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="content-container">
        <div class="form-section">
            <div class="button-container">
                <a href="game.php" class="btn back-btn">Back</a>
            </div>

            <h2>Update Game</h2>

            <form method="post" action="" class="form-container">
                <input type="hidden" name="idgame" value="<?php echo htmlspecialchars($idgame); ?>">
                
                <div class="form-group">
                    <div class="input-group">
                        <label for="name_game">Game Name</label>
                        <input type="text" id="name_game" name="name_game" value="<?php echo htmlspecialchars($name_game);?>" required>
                    </div>

                    <div class="input-group">
                        <label for="description_game">Game Description</label>
                        <textarea id="description_game" name="description_game" required><?php echo htmlspecialchars($description_game); ?></textarea>
                    </div>
                </div>
                
                <button type="submit" class="submit-btn">Update Game</button>
            </form>
        </div>
    </div>
</body>
</html>