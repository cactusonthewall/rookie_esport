<?php
require_once("class/event.php");
require_once("class/data.php");

$limit = 5;
$event = new Event();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Event</title>
    <link rel="stylesheet" type="text/css" href="css/event.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="content-container">
        <div class="form-section">
            <div class="button-container"> 
                <a href="admin_dashboard.php" class="btn back-btn">Back</a>
            </div>

            <form method="post" action="event_proses.php" class="form-container">
                <p><label>Event Name</label> <input type="text" name="name_event" required></p>
                <p><label>Event Date</label> <input type="date" name="date_event" required></p>
                <p><label>Event Description</label> <input type="text" name="description_event" required></p>
                <p><button type="submit" name="submit" value="simpan">Insert</button></p>
            </form>

            <form method="get" action="" class="search-form">
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

            $result = $event->getEvent($search, $offset, $limit);
            if ($result->num_rows > 0) {
                echo "<table>
                        <tr> <th>Event Name</th><th>Event Date</th><th>Event Description</th><th>Actions</th> </tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                    echo "<td>
                        <div class='action-group'>
                            <a href='event_update.php?idevent=" . $row['idevent'] . "' class='update'>Update</a>  
                            <a href='event_delete.php?idevent=" . $row['idevent'] . "' class='delete' onclick='return confirm(\"Yakin ingin menghapus?\")'>Delete</a>
                        </div>
                    </td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "No events found.";
            }
            ?>
        </div>

        <?php
        $result = $event->getEvent($search);
        $total_data = $result->num_rows;
        include "paging.php";
        echo '<div class="pagination">';
        echo generate_page($search, $total_data, $limit, $no_hal); 
        echo '</div>';
        ?>
    </div>
</body>
</html>
