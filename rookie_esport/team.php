<?php 
require_once("class/team.php");
require_once("class/data.php");

$limit = 5;
$team = new Team();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Teams</title>
    <link rel="stylesheet" type="text/css" href="css/teams.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="content-container">
        <div class="form-section">
            <div class="button-container"> 
                <a href="admin_dashboard.php" class="btn back-btn">Back</a>
                <a href="event_teams.php" class="btn team-btn">Team - Event</a>
            </div>

            <form method="post" action="team_proses.php" enctype="multipart/form-data" class="form-container">
                <p><label>Team Name</label> <input type="text" name="name_team" required></p>
                <p><label>Select Game</label>
                    <select name="idgame" required>
                        <?php 
                            $team->selectGame($arrKolom);
                        ?>
                    </select>
                </p>
                <p><label>Upload Image</label> <input type="file" name="team_image" accept=".jpg"></p>
                <p><button type="submit" name="submit" value="simpan">Insert</button></p>
            </form>

            <form method="get" action="" class="search-form">
                <input type="text" name="search" placeholder="Search team name" value="<?php if(isset($_GET['search'])){echo $_GET['search']; } ?>">
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
            $result = $team->getTeam($search, $offset, $limit);
            if ($result->num_rows > 0) {
                echo "<table>
                        <tr> <th>Team Name</th><th>Game Name</th><th>Team Photo</th><th>Actions</th> </tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['game_name']) . "</td>";
                    $image_path = "uploads/team_images/" . $row['idteam'] . ".jpg";

                    if (file_exists($image_path)) {
                        $image_path = $image_path . "?" . filemtime($image_path);
                        echo "<td><img src='" . htmlspecialchars($image_path) . "' alt='Team Image' width='100'></td>";
                    } else {
                        echo "<td>No image</td>";
                    }

                    echo "<td>
                        <div class='action-group'>
                            <a href='team_update.php?idteam=" . $row['idteam'] . "' class='update'>Update</a>  
                            <a href='team_delete.php?idteam=" . $row['idteam'] . "' class='delete' onclick='return confirm(\"Yakin ingin menghapus?\")'>Delete</a>
                        </div>
                        <div class='action-group'>
                            <a href='team_achievements.php?idteam=" . $row['idteam'] . "' class='achievements'>Achievements</a> 
                            <a href='team_event.php?idteam=" . $row['idteam'] . "' class='events'>Event</a>
                        </div>
                        </td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "No teams found.";
            }
            ?>
        </div>

        <?php
        $result = $team->getTeam($search);
        $total_data = $result->num_rows;
        include "paging.php";
        echo '<div class="pagination">';
            echo generate_page($search, $total_data, $limit, $no_hal); 
        echo '</div>';
        ?>
    </div>
</body>
</html>
