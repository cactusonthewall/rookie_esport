<?php 
require_once("parent.php");

class Team extends ParentClass {
	public function __construct() {
        parent::__construct();
    }

    public function addTeam($arrKolom){
        $sql = "Insert into team (idgame, name) VALUES (?, ?)";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("is", $arrKolom['idgame'], $arrKolom['name_team']);
        if ($stmt->execute()) {
            echo "Data team berhasil ditambahkan!";
        } else {
            echo "Gagal menambahkan data: " . $stmt->error;
        }
        $last_id = $stmt->insert_id; 
    
        if (isset($_FILES['team_image']) && $_FILES['team_image']['error'] == 0) {
            $target_dir = "uploads/team_images/";
            $target_file = $target_dir . $last_id . ".jpg";
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            
            if ($imageFileType == "jpg") {
                if (move_uploaded_file($_FILES["team_image"]["tmp_name"], $target_file)) {
                    echo "The file " . basename($_FILES["team_image"]["name"]) . " has been uploaded.";
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            } else {
                echo "Sorry, only JPG files are allowed.";
            }
        }
    
        return $last_id;
    }
    
	

	public function getTeam($cari = "", $offset = null, $limit = null) {
        $cari_persen = "%" . $cari . "%";
        if (is_null($limit)) {
            $stmt = $this->mysqli->prepare("
                SELECT t.idteam, t.name, t.idgame, g.name AS game_name
                FROM team t
                LEFT JOIN game g ON t.idgame = g.idgame
                WHERE t.name LIKE ?
            ");
            $stmt->bind_param("s", $cari_persen);
        } else {
            $stmt = $this->mysqli->prepare("
                SELECT t.idteam, t.name, t.idgame, g.name AS game_name
                FROM team t
                LEFT JOIN game g ON t.idgame = g.idgame
                WHERE t.name LIKE ?
                LIMIT ?, ?
            ");
            $stmt->bind_param("sii", $cari_persen, $offset, $limit);
        }
        $stmt->execute();
        $res = $stmt->get_result(); 
        return $res;
    }    

    public function getGameDetailsById($idgame) {
        $sql = "SELECT idgame, name FROM game WHERE idgame = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $idgame);
        $stmt->execute();
        $result = $stmt->get_result();
        return $res;
    }

    public function deleteTeam($arrKolom){
    	$sql = "Delete FROM team WHERE idteam = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param('i', $arrKolom['idteam']);
        if ($stmt->execute()) {
            echo "Data team berhasil dihapus!";
        } else {
            echo "Gagal menghapus data: " . $stmt->error;
        }
    }

    public function updateTeam($arrKolom) {
        $sql = "Update team SET name = ?, idgame = ? WHERE idteam = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("sii", $arrKolom['name_team'], $arrKolom['idgame'], $arrKolom['idteam']);
        
        if ($stmt->execute()) {
            echo "Team data has been updated!";

            if (isset($_FILES['team_image']) && $_FILES['team_image']['error'] == 0) {
                $target_dir = "uploads/team_images/";
                $target_file = $target_dir . $arrKolom['idteam'] . ".jpg"; 
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
                if ($imageFileType == "jpg") {
                    
                    $old_image_path = $target_dir . $arrKolom['idteam'] . ".jpg";
                    if (file_exists($old_image_path)) {
                        unlink($old_image_path);
                        echo "Old image deleted.";
                    }
    
                    if (move_uploaded_file($_FILES["team_image"]["tmp_name"], $target_file)) {
                        echo "The file " . basename($_FILES["team_image"]["name"]) . " has been uploaded.";
                    } else {
                        echo "Sorry, there was an error uploading your file.";
                    }
                } else {
                    echo "Sorry, only JPG files are allowed.";
                }
            } else {
                echo "No image uploaded or there was an error with the uploaded file.";
            }
            
            header("Location: team.php"); 
            exit();
        } else {
            echo "Failed to update team data: " . $stmt->error;
        }
    }

    public function selectGame($arrKolom) {
        $stmt = $this->mysqli->prepare("SELECT * FROM game");
        $stmt->execute();
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc()) {
            echo "<option value='".$row['idgame']."'>".$row['name']."</option>";
        }
        $stmt->close();
    }

    public function selectGameUpdate($arrKolom){
        $sql = "Select * From game";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            echo "<option value='".$row['idgame']."' ".($row['idgame'] == $team['idgame'] ? "selected" : "").">".$row['name']."</option>";
        }
    }

    public function selectTeam($arrKolom) {
        $idteam = $arrKolom['idteam']; 
        $sql = "SELECT t.idteam, t.name, t.idgame, g.name AS game_name FROM team t
                INNER JOIN game g ON t.idgame = g.idgame WHERE t.idteam = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $idteam);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc(); 
    }
    

    public function addTeamToEvent($arrKolom) {
        $sql = "Insert into event_teams (idevent, idteam) VALUES (?, ?)";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("ii", $arrKolom['idevent'], $arrKolom['idteam']);
        $stmt->execute();
    }

    public function getAllTeams() {
        $sql = "SELECT idteam, name FROM team";
        $result = $this->mysqli->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getTeamName($idteam) {
        $sql = "Select name FROM team WHERE idteam = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $idteam);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row ? $row['name'] : 'Unknown Team';
    }


    public function addMemberToTeam($memberId, $teamId, $description) {
        $sql = "Insert INTO team_members (idteam, idmember, description) VALUES (?, ?, ?)";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("iis", $teamId, $memberId, $description);
        return $stmt->execute();
    }

    public function getTotalTeams() {
        $sql = "SELECT COUNT(*) as total FROM team";
        $result = $this->mysqli->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'];
    }
}
?>