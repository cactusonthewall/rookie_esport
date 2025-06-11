<?php
session_start();
require_once("class/member.php");
require_once("class/team.php");
require_once("class/team_members.php");

if (!isset($_SESSION['username']) || $_SESSION['profile'] !== 'member') {
    header("Location: login.php");
    exit();
}

$memberId = $_SESSION['idmember'];
$member = new Member();
$teamMember = new TeamMember();
$team = new Team();

$approvedTeam = $teamMember->getApprovedTeamByMemberId($memberId);

if (!$approvedTeam) {
    echo "<p>Please join a team or wait for approval of your team proposal.</p>";
    $_SESSION['approved_team'] = null;
} else {
    $_SESSION['approved_team'] = $approvedTeam['idteam'];
}

if (isset($_GET['selected_team'])) {
    $_SESSION['selected_team'] = $_GET['selected_team'];
}

$selectedTeamId = $_SESSION['selected_team'] ?? $_SESSION['approved_team'];

$memberName = $member->getMemberName($memberId);
$proposals = $teamMember->getJoinProposalsByMemberId($memberId);
$proposalsList = $proposals ? $proposals->fetch_all(MYSQLI_ASSOC) : [];

$teamsResult = $teamMember->getTeamsByMemberId($memberId);
$teams = $teamsResult ? $teamsResult->fetch_all(MYSQLI_ASSOC) : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Member Dashboard</title>
    <link rel="stylesheet" type="text/css" href="css/memdashhb.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="button-container"> 
        <a href="logout.php" class="logout-link">Logout</a>
    </div>

    <h1>Welcome, <?= htmlspecialchars($memberName) ?></h1>

    <?php if (empty($teams)): ?>
        <p>Anda belum terdaftar di team mana pun.</p>
        <!-- <p><a href="member_proposal.php">Join Team</a></p> -->
    <?php else: ?>
        <div class="team-selector">
            <form method="get">
                <select name="selected_team" id="selected_team" onchange="this.form.submit()">
                    <?php foreach ($teams as $teamInfo): ?>
                        <option value="<?= $teamInfo['idteam'] ?>" 
                                <?= ($selectedTeamId == $teamInfo['idteam']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($teamInfo['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>

        <?php if ($selectedTeamId): ?>
            <div class="card-container">
                <div class="card">
                    <h2><?= htmlspecialchars($team->getTeamName($selectedTeamId)) ?></h2>
                    <?php 
                    $imagePath = "uploads/team_images/{$selectedTeamId}.jpg";
                    $update_image_path = $imagePath . "?" . filemtime($imagePath);
                    if (file_exists($imagePath)): ?>
                        <img src="<?= $update_image_path ?>" alt="Team Image" style="height: 200px;">
                    <?php else: ?>
                        <p>No team image available.</p>
                    <?php endif; ?>
                    <ul>
                        <li><a href='team_member.php?team_id=<?= $selectedTeamId ?>'>Team Members</a></li>
                        <li><a href='member_team_achievement.php?idteam=<?= $selectedTeamId ?>'>Team Achievements</a></li>
                        <li><a href='member_team_event.php?idteam=<?= $selectedTeamId ?>'>Team Events</a></li>
                    </ul>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <p><a href="member_proposal.php">Join Team</a></p>

    <div class="card-container">
        <h2>Proposal History</h2>
        <?php if (empty($proposalsList)): ?>
            <p>No proposals submitted yet.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Team Name</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($proposalsList as $proposal): ?>
                        <tr>
                            <td><?= htmlspecialchars($proposal['team_name']) ?></td>
                            <td><?= htmlspecialchars(ucfirst($proposal['status'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
</body>
</html>