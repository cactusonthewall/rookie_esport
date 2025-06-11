<?php
session_start();
require_once("class/member.php");
require_once("class/team.php");
require_once("class/team_members.php");
require_once("class/achievement.php");
require_once("class/data.php");

if (!isset($_SESSION['username']) || $_SESSION['profile'] !== 'member') {
    header("Location: login.php");
    exit();
}

$limit = 5; 
$memberId = $_SESSION['idmember'];
$teamId = isset($_GET['idteam']) ? intval($_GET['idteam']) : 0;

$member = new Member();
$teamMember = new TeamMember();
$team = new Team();
$achievement = new Achievement();

$memberName = $member->getMemberName($memberId);
$teamName = $team->getTeamName($teamId);


$no_hal = (isset($_GET['page']) && is_numeric($_GET['page'])) ? $_GET['page'] : 1;
$offset = ($no_hal - 1) * $limit; 

$achievementsResult = $achievement->getAchievementTeams($teamId, "", $offset, $limit);

$total_data = $achievement->countAchievementTeams($teamId);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Achievement Team - <?= htmlspecialchars($teamName) ?></title>
    <link rel="stylesheet" type="text/css" href="css/mem_achievement.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="content-container">
        <div class="form-section">
            <div class="button-container">
                <a href="member_dashboard.php" class="btn back-btn">Back</a>
            </div>
            
            <h3>Achievements of <?= htmlspecialchars($teamName) ?></h3>
        </div>

        <div class="table-wrapper">
            <?php
            if ($achievementsResult->num_rows == 0) {
                echo "<p style='text-align: center;'>No achievements available for this team.</p>";
            } else {
                echo "<table>
                    <tr>
                        <th>Achievement Name</th>
                        <th>Date</th>
                        <th>Description</th>
                    </tr>";
                while ($row = $achievementsResult->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            }
            ?>
        </div>

        <?php
        include "paging.php";
        echo '<div class="pagination">';
        echo generate_page_id("", $total_data, $limit, $no_hal, $teamId);
        echo '</div>';
        ?>
    </div>
</body>
</html>
