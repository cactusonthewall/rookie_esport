<?php 
require_once("parent.php");

class Game extends ParentClass {
    public function __construct() {
        parent::__construct();
    }

    public function addGame($arrKolom) {
        $sql = "Insert game (name, description) Values (?, ?)";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param('ss', $arrKolom['nama_game'], $arrKolom['description_game']);
        if ($stmt->execute()) {
            echo "Data game berhasil ditambahkan!";
        } else {
            echo "Gagal menambahkan data: " . $stmt->error;
        }
        $last_id = $stmt->insert_id; 
        return $last_id;
    }

    public function getGame($cari = "", $offset = null, $limit = null) {
        $cari_persen = "%" . $cari . "%";
        if (is_null($limit)) {
            $stmt = $this->mysqli->prepare("Select * from game WHERE name LIKE ?");
            $stmt->bind_param("s", $cari_persen);
        } else {
            $stmt = $this->mysqli->prepare("Select * from game WHERE name LIKE ? LIMIT ?, ?");
            $stmt->bind_param("sii", $cari_persen, $offset, $limit);
        }
        $stmt->execute();
        $res = $stmt->get_result(); 
        return $res;
    }

    public function deleteGame($arrKolom) {
        $sql = "Delete FROM game WHERE idgame = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param('i', $arrKolom['idgame']);
        if ($stmt->execute()) {
            echo "Data game berhasil dihapus!";
        } else {
            echo "Gagal menghapus data: " . $stmt->error;
        }
    }

    public function updateGame($arrKolom) {
        $sql = "Update game Set name = ?, description = ? Where idgame = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("ssi", $arrKolom['name_game'], $arrKolom['description_game'], $arrKolom['idgame']);
        
        if ($stmt->execute()) {
            header("Location: game.php");
            exit();
        } else {
            echo "Failed to execute statement: " . $stmt->error;
        }
    }

    public function selectGame($arrKolom) {
    $idgame = $arrKolom['idgame']; 
    $sql = "Select * FROM game WHERE idgame = ?";
    $stmt = $this->mysqli->prepare($sql);
    $stmt->bind_param("i", $idgame);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_assoc();
    }

    public function getTotalGames() {
        $sql = "SELECT COUNT(*) as total FROM game";
        $result = $this->mysqli->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'];
    }
}
?>
