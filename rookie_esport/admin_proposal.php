<?php
session_start();
require_once("class/proposal.php");
require_once("class/team_members.php");

if (!isset($_SESSION['username']) || $_SESSION['profile'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$limit = 5; 
$proposal = new Proposal();
$teamMember = new TeamMember();
$proposals = $proposal->getProposalList();

if (isset($_POST['action'])) {
    $proposalId = $_POST['proposal_id'];
    $action = $_POST['action'];

    if ($action === 'approve') {
        $details = $proposal->getProposalDetails($proposalId);
        $memberId = $details['idmember'];
        $teamId = $details['idteam'];
        $description = $details['description'];
    
        if ($proposal->updateProposalStatus($proposalId, 'approved')) {
            if ($teamMember->addTeamMember($teamId, $memberId, $description)) {
                echo "<p>Proposal telah disetujui dan anggota telah ditambahkan ke tim.</p>";
            } else {
                echo "<p>Proposal telah disetujui, tetapi gagal menambahkan anggota ke tim.</p>";
            }
        } else {
            echo "<p>Gagal menyetujui proposal.</p>";
        }
    } elseif ($action === 'reject') {
        if ($proposal->updateProposalStatus($proposalId, 'rejected')) {
            echo "<p>Proposal telah ditolak.</p>";
        } else {
            echo "<p>Gagal menolak proposal.</p>";
        }
    }
}

$no_hal = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$offset = ($no_hal * $limit) - $limit;

$search = isset($_GET['search']) ? $_GET['search'] : ""; 

if ($search) {
    echo "<i>Hasil pencarian untuk: " . htmlspecialchars($search) . "</i>";
}

$result = $proposal->getProposal($search, $offset, $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Proposal Management</title>
    <link rel="stylesheet" href="css/adminpro.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="content-container">
        <div class="button-container">
            <a href="admin_dashboard.php" class="btn back-btn">Back</a>
        </div>

        <form method="get" action="" class="search-form">
            <input type="text" name="search" placeholder="Search status" value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="btn search-btn">Search</button>
        </form>

        <div class="table-wrapper">
            <?php
            if ($result->num_rows > 0) {
                echo "<table>
                        <tr><th>Member Name</th><th>Team Name</th><th>Description</th><th>Status</th><th>Action</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['fname']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['team_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                    echo "<td>
                            <form method='post'>
                                <input type='hidden' name='proposal_id' value='" . $row['idjoin_proposal'] . "'>
                                <button type='submit' name='action' value='approve' class='btn proposal-btn'>Approve</button>
                                <button type='submit' name='action' value='reject' class='btn proposal-btn'>Reject</button>
                            </form>
                          </td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "No proposals found.";
            }
            ?>
        </div>

        <?php
        $result = $proposal->getProposal($search);
        $total_data = $result->num_rows;
        include "paging.php"; 
        echo '<div class="pagination">';
        echo generate_page_proposal($search, $total_data, $limit, $no_hal); 
        echo '</div>';
        ?>
    </div>
</body>
</html>
