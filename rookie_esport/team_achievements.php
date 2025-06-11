<?php 
require_once("class/achievement.php");
require_once("class/data.php");

$limit = 5;
$achievement = new Achievement();

if (isset($_GET['idteam'])) {
    $idteam = $_GET['idteam'];
    $nama_team = $achievement->getTeamName($idteam);
} else {
    header("Location: team.php");
    exit();
}

$no_hal = (isset($_GET['page'])) ? $_GET['page'] : 1;
if (!is_numeric($no_hal)) {
    $no_hal = 1;
}
$offset = ($no_hal * $limit) - $limit;

$search = "";
if (isset($_GET['search'])) {
    $search = $_GET['search'];
}

$result = $achievement->getAchievementTeams($idteam, $search, $offset, $limit);
$result_total = $achievement->getAchievementTeams($idteam, $search);
$total_data = $result_total->num_rows;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Team Achievements</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/team_achievement.css">
</head>
<body>
    <div class="content-container">
        <div class="form-section">
            <div class="button-container">
                <a href="team.php" class="btn back-btn">Back</a>
            </div>
            
            <h3>Achievements for <?php echo htmlspecialchars($nama_team); ?></h3>

            <form method="get" action="" class="search-form">
                <input type="hidden" name="idteam" value="<?php echo htmlspecialchars($idteam); ?>">
                <input type="text" name="search" placeholder="Search achievements" 
                       value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <button type="submit">Search</button>
            </form>

            <?php 
            if (isset($_GET['search'])) {
                echo "<i>Search results for: " . htmlspecialchars($search) . "</i>";
            }
            ?>
        </div>

        <div class="table-wrapper">
            <?php
            if ($result->num_rows > 0) {
                echo "<table>
                    <tr>
                        <th>Event Name</th>
                        <th>Date</th>
                        <th>Description</th>
                    </tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p style='text-align: center;'>No achievements found.</p>";
            }
            ?>
        </div>

        <div class="pagination">
            <?php
            include "paging.php";
            echo generate_page_id($search, $total_data, $limit, $no_hal, $idteam);
            ?>
        </div>
    </div>
</body>
</html>
