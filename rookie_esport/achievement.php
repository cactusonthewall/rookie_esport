<?php 
require_once("class/achievement.php");
require_once("class/data.php");

$limit = 5;
$achievement = new Achievement();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Achievement</title>
    <link rel="stylesheet" type="text/css" href="css/achievementt.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="content-container">
        <div class="form-section">
            <div class="button-container"> 
                <a href="admin_dashboard.php" class="btn back-btn">Back</a>
            </div>

            <form method="post" action="achievement_proses.php" class="form-container">
                <p><label>Achievement Name</label> <input type="text" name="name_achievement" required></p>
                <p><label>Achievement Date</label> <input type="date" name="date_achievement" required></p>
                <p><label>Achievement Description</label> <input type="text" name="description_achievement" required></p>
                <p><label>Select Team</label>
                    <select name="idteam" required>
                        <?php 
                            $achievement->selectTeam($arrKolom);
                        ?>
                    </select>
                </p>
                <p><button type="submit" name="submit" value="simpan">Insert</button></p>
            </form>

            <form method="get" action="" class="search-form">
                <input type="text" name="search" placeholder="Search achievement name" value="<?php if(isset($_GET['search'])){echo $_GET['search']; } ?>">
                <button type="submit">Search</button>
            </form>

            <?php 
            if(isset($_GET['search'])) {
                $search = $_GET['search'];
                echo "<i>Search results for: $search</i>";
            }
            ?>
        </div>

        <div class="table-wrapper">
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

            $result = $achievement->getAchievement($search, $offset, $limit);
            if ($result->num_rows > 0) {
                echo "<table>
                        <tr> <th>Team Name</th><th>Achievement Name</th><th>Achievement Date</th><th>Achievement Description</th><th>Actions</th> </tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['team_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                    echo "<td>
                            <a href='achievement_update.php?idachievement=" . $row['idachievement'] . "' class='update'>Update</a>   
                            <a href='achievement_delete.php?idachievement=" . $row['idachievement'] . "' class='delete' onclick='return confirm(\"Are you sure you want to delete?\")'>Delete</a>
                          </td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "No achievements found.";
            }
            ?>
        </div>

        <?php
        $result = $achievement->getAchievement($search);
        $total_data = $result->num_rows;
        include "paging.php";
        echo '<div class="pagination">';
            echo generate_page($search, $total_data, $limit, $no_hal); 
        echo '</div>';
        ?>
    </div>
</body>
</html>
