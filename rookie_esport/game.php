<?php 
require_once("class/game.php");
require_once("class/data.php");

$limit = 5;
$game = new Game();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Games</title>
    <link rel="stylesheet" type="text/css" href="css/gamess.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="content-container">
        <div class="form-section">
            <div class="button-container"> 
                <a href="admin_dashboard.php" class="btn back-btn">Back</a>
            </div>

            <form method="post" action="game_proses.php" class="form-container">
                <p><label>Game Name</label> <input type="text" name="name_game" required></p>
                <p><label>Game Description</label> <input type="text" required></p>
                <p><button type="submit" name="submit" value="simpan">Insert</button></p>
            </form>

            <form method="get" action="" class="search-form">
                <input type="text" name="search" placeholder="Search game name" value="<?php if(isset($_GET['search'])){echo $_GET['search']; } ?>">
                <button type="submit">Search</button>
            </form>

            <?php 
            if(isset($_GET['search'])) {
                $search = $_GET['search'];
                echo "<i>Search results for: $search</i>";
            }
            ?>
        </div>

        <?php 
        $no_hal = (isset($_GET['page'])) ? $_GET['page'] : 1;
        if(!is_numeric($no_hal)){
            $no_hal = 1;
        } else{
            $offset = ($no_hal*$limit)-$limit;
        }

        $search = "";
        if(isset($_GET['search'])) {
            $search = $_GET['search'];
        }
        ?>

        <div class="table-wrapper">
            <?php
            $result = $game->getGame($search, $offset, $limit);
            if ($result->num_rows > 0) {
                echo "<table>
                        <tr> <th>Game Name</th><th>Game Description</th><th>Actions</th> </tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['description']) . "</td>";

                    echo "<td>
                        <div class='action-group'>
                            <a href='game_update.php?idgame=" . $row['idgame'] . "' class='update'>Update</a>  
                            <a href='game_delete.php?idgame=" . $row['idgame'] . "' class='delete' onclick='return confirm(\"Are you sure you want to delete?\")'>Delete</a>
                        </div>
                        </td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "No games found.";
            }
            ?>
        </div>

        <?php
        $result = $game->getGame($search);
        $total_data = $result->num_rows;
        include "paging.php";
        echo '<div class="pagination">';
            echo generate_page($search, $total_data, $limit, $no_hal); 
        echo '</div>';
        ?>
    </div>
</body>
</html>
