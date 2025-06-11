<?php
session_start();
require_once("class/member.php");
require_once("class/team.php");
require_once("class/team_members.php");
require_once("class/data.php");

if (!isset($_SESSION['username']) || $_SESSION['profile'] !== 'member') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['team_id'])) {
    header("Location: member_dashboard.php");
    exit();
}

$limit = 5;
$teamId = $_GET['team_id'];
$search = "";
$teamMember = new TeamMember();

$no_hal = (isset($_GET['page'])) ? $_GET['page'] : 1;
if(!is_numeric($no_hal)){
    $no_hal = 1;
}
$offset = ($no_hal * $limit) - $limit;
$members = $teamMember->getMembersByTeamId($teamId, $offset, $limit);

$team = new Team();
$teamInfo = $team->selectTeam(['idteam' => $teamId]);
$teamName = $teamInfo ? htmlspecialchars($teamInfo['name']) : 'Unknown Team';

$result_total = $teamMember->getTeamMembers($teamId, $search);
$total_data = $result_total->num_rows;
$result = $teamMember->getTeamMembers($teamId, $search, $offset, $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Members - <?= $teamName ?></title>
    <link rel="stylesheet" type="text/css" href="css/team_member.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="content-container">
        <div class="form-section">
            <div class="button-container">
                <a href="member_dashboard.php" class="btn back-btn">Back</a>
            </div>
            
            <h3>Members of <?php echo $teamName; ?></h3>

            <form method="get" action="" class="search-form">
                <input type="hidden" name="team_id" value="<?php echo $teamId; ?>">
                <input type="text" name="search" placeholder="Search member name" value="<?php if(isset($_GET['search'])){echo htmlspecialchars($_GET['search']); } ?>">
                <button type="submit">Search</button>
            </form>

            <?php 
            if(isset($_GET['search'])) {
                $search = $_GET['search'];
                echo "<i>Search results for: " . htmlspecialchars($search) . "</i>";
            }
            ?>
        </div>

        <div class="table-wrapper">
            <?php
            if (!empty($members)) {
                echo "<table>
                    <tr>
                        <th>Member Name</th>
                    </tr>";
                foreach ($members as $memberInfo) {
                    $memberName = htmlspecialchars($memberInfo['fname'] . ' ' . $memberInfo['lname']);
                    echo "<tr>";
                    echo "<td>$memberName</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p style='text-align: center;'>No members found in this team.</p>";
            }
            ?>
        </div>

        <?php
        include "paging.php";
        echo '<div class="pagination">';
        echo generate_page_member($search, $total_data, $limit, $no_hal, $teamId);
        echo '</div>';
        ?>
    </div>
</body>
</html>