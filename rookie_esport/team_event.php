<?php
require_once("class/event.php");
require_once("class/data.php");
$limit = 5;
$event = new Event();
if (isset($_GET['idteam'])) {
    $idteam = $_GET['idteam'];
    $name_team = $event->getTeamName($idteam);
    // if (!$name_team) {
    //     header("Location: team.php");
    // }
} else {
    header("Location: team.php");
}

$no_hal = (isset($_GET['page'])) ? $_GET['page'] : 1;
if(!is_numeric($no_hal)){
    $no_hal = 1;
}
$offset = ($no_hal * $limit) - $limit;
$search = "";
$result = $event->getEventTeams($idteam,$search, $offset, $limit);
$result_total = $event->getEventTeams($idteam, $search);
$total_data = $result_total->num_rows;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Team Events</title>
    <link rel="stylesheet" type="text/css" href="css/team_event.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="content-container">
        <div class="form-section">
            <div class="button-container">
                <a href="team.php" class="btn back-btn">Back</a>
            </div>
            
            <h3>Events for <?php echo htmlspecialchars($name_team); ?></h3>

            <form method="get" action="" class="search-form">
                <input type="hidden" name="idteam" value="<?php echo $idteam; ?>">
                <input type="text" name="search" placeholder="Search event name" value="<?php if(isset($_GET['search'])){echo $_GET['search']; } ?>">
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
            if ($result->num_rows > 0) {
                echo "<table>
                    <tr>
                        <th>Event Name</th>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>";
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>".htmlspecialchars($row['name'])."</td>";
                    echo "<td>".htmlspecialchars($row['date'])."</td>";
                    echo "<td>".htmlspecialchars($row['description'])."</td>";
                    echo "<td>
                        <div class='action-group'>
                            <a href='team_event_update.php?idteam=".$idteam."&idevent=".$row['idevent']."' class='update'>Update</a>
                            <a href='team_event_delete.php?idteam=".$idteam."&idevent=".$row['idevent']."' class='delete' onclick='return confirm(\"Are you sure you want to delete this event?\")'>Delete</a>
                        </div>
                        </td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p style='text-align: center;'>No events found.</p>";
            }
            ?>
        </div>

        <?php
        include "paging.php";
        echo '<div class="pagination">';
        echo generate_page_id($search, $total_data, $limit, $no_hal, $idteam);
        echo '</div>';
        ?>
    </div>
</body>
</html>
