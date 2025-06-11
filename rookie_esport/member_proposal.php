<?php 
session_start();
require_once("class/team.php");
require_once("class/proposal.php");
require_once("class/member.php"); 

if (!isset($_SESSION['username']) || $_SESSION['profile'] !== 'member') {
    header("Location: login.php");
    exit();
}

$team = new Team();
$proposal = new Proposal();
$member = new Member(); 
$teams = $team->getAllTeams();
$memberId = $_SESSION['idmember']; 
$fullName = $member->getMemberName($memberId); 

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedTeamId = $_POST['team_id'];
    $description = $_POST['description']; 
    
    $status = "waiting"; 

    if ($proposal->addProposal($memberId, $selectedTeamId, $description, $status)) {
        $message = "Anda telah mengajukan permohonan untuk bergabung dengan tim. Status anda 'waiting'";
    } else {
        $message = "Gagal mengajukan permohonan. Silakan coba lagi.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Dashboard</title>
    <link rel="stylesheet" type="text/css" href="css/mempropo.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="button-container">
        <a href="member_dashboard.php" class="logout-link">Back</a>
    </div>

    <h1>Welcome, <?= htmlspecialchars($fullName) ?></h1>

    <?php if ($message): ?>
        <div class="message">
            <p><?= $message; ?></p>
        </div>
    <?php endif; ?>

    <div class="proposal-form">
        <form method="post">
            <label for="team_id">Select Team:</label>
            <select name="team_id" id="team_id" required>
                <?php foreach ($teams as $team): ?>
                    <option value="<?= $team['idteam']; ?>"><?= htmlspecialchars($team['name']); ?></option>
                <?php endforeach; ?>
            </select>
            
            <label for="description">Description:</label>
            <textarea name="description" id="description" required></textarea> 
            
            <button type="submit">Join</button>
        </form>
    </div>
</div>
</body>
</html>
