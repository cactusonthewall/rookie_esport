<?php 
require_once("parent.php");

class Achievement extends ParentClass {
	public function __construct() {
        parent::__construct();
    }

    public function addAchievement($arrKolom){
    	$sql = "Insert into achievement (idteam, name, date, description) VALUES (?, ?, ?, ?)";
    	$stmt = $this->mysqli->prepare($sql);
		$stmt->bind_param("isss", $arrKolom['idteam'], $arrKolom['name_achievement'], $arrKolom['date_achievement'], $arrKolom['description_achievement']);
		if ($stmt->execute()) {
		    echo "Data achievement berhasil ditambahkan!";
		} else {
		    echo "Gagal menambahkan data: " . $stmt->error;
		}
		$last_id = $stmt->insert_id; 
        return $last_id;
	}
	
	public function getAchievement($cari = "", $offset = null, $limit = null) {
        $cari_persen = "%" . $cari . "%";
        if (is_null($limit)) {
            $stmt = $this->mysqli->prepare("Select a.idachievement, a.name, a.date, a.description, t.name AS team_name
                FROM achievement a
                INNER JOIN team t ON a.idteam = t.idteam
                WHERE a.name LIKE ?");
            $stmt->bind_param("s", $cari_persen);
        } else {
            $stmt = $this->mysqli->prepare("Select a.idachievement, a.name, a.date, a.description, t.name AS team_name
                FROM achievement a
                INNER JOIN team t ON a.idteam = t.idteam
                WHERE a.name LIKE ?
                LIMIT ?, ?");
            $stmt->bind_param("sii", $cari_persen, $offset, $limit);
        }
        $stmt->execute();
        $res = $stmt->get_result(); 
        return $res;
    }	  

    public function deleteAchievement($arrKolom){
    	$sql = "Delete FROM achievement WHERE idachievement = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param('i', $arrKolom['idachievement']);
        if ($stmt->execute()) {
            echo "Data achievement berhasil dihapus!";
        } else {
            echo "Gagal menghapus data: " . $stmt->error;
        }
    }

    public function updateAchievement($arrKolom) {
        $sql = "Update achievement Set name = ?, date = ?, description = ?, idteam = ? Where idachievement = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("sssii", $arrKolom['name_achievement'], $arrKolom['date_achievement'], $arrKolom['description_achievement'], $arrKolom['idteam'], $arrKolom['idachievement']);
        
        if ($stmt->execute()) {
            header("Location: achievement.php");
            exit();
        } else {
            echo "Failed to execute statement: " . $stmt->error;    
        }
    }

    public function selectAchievement($arrKolom) {
        $idachievement = $arrKolom['idachievement']; 
        $sql = "Select * FROM achievement WHERE idachievement = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $idachievement);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function selectTeam($arrKolom) {
        $sql = "Select * From team";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc()) {
            echo "<option value='".$row['idteam']."'>".$row['name']."</option>";
        }
        $stmt->close();        
    }

    public function selectTeamUpdate($arrKolom){
        $sql = "Select * From team";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            echo "<option value='".$row['idteam']."' ".($row['idteam'] == $achievement['idteam'] ? "selected" : "").">".$row['name']."</option>";
        }
    }

    public function getTeamName($idteam) {
        $query = "Select name FROM team WHERE idteam = ?";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("i", $idteam);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['name'];
        } else {
            return "Unknown Team";
        }
    }

    public function getAchievementTeams($idteam, $cari = "", $offset = null, $limit = null) {
        $cari_persen = "%" . $cari . "%";
        if (is_null($limit)) {
            $stmt = $this->mysqli->prepare("
                SELECT a.idachievement, a.name, a.date, a.description
                FROM achievement a
                JOIN team t ON t.idteam = a.idteam
                WHERE t.idteam = ?
                AND a.name LIKE ?
            ");
            $stmt->bind_param('is', $idteam, $cari_persen);
        } else {
            $stmt = $this->mysqli->prepare("
                SELECT a.idachievement, a.name, a.date, a.description
                FROM achievement a
                JOIN team t ON t.idteam = a.idteam
                WHERE t.idteam = ?
                AND a.name LIKE ?
                LIMIT ?, ?
            ");
            $stmt->bind_param('isii', $idteam, $cari_persen, $offset, $limit);
        }
        $stmt->execute();
        $res = $stmt->get_result();
        return $res;
    }
    public function countAchievementTeams($idteam) {
        $stmt = $this->mysqli->prepare("
            SELECT COUNT(*) as total FROM achievement a
            JOIN team t ON t.idteam = a.idteam
            WHERE t.idteam = ?
        ");
        $stmt->bind_param('i', $idteam);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total'];
    }
    
    public function getAllAchievements() {
        $sql = "Select * FROM achievement ORDER BY name";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->execute();
        return $stmt->get_result();
    }
    public function getTotalAchievements() {
        $sql = "SELECT COUNT(*) as total FROM achievement";
        $result = $this->mysqli->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'];
    }
}
?>