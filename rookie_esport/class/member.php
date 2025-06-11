<?php 
require_once("parent.php");

class Member extends ParentClass {
    public function __construct() {
        parent::__construct();
    }

    public function getMemberByUsername($username) {
        $sql = "Select * FROM member WHERE username = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function addMember($arrKolom){
        $hashedPassword = password_hash($arrKolom['password'], PASSWORD_DEFAULT);
        $profile = 'member';
        $sql = "Insert INTO member (fname, lname, username, password, profile) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("sssss", $arrKolom['fname'], $arrKolom['lname'], $arrKolom['username'], $hashedPassword, $profile);
        if ($stmt->execute()) {
            echo "Data member berhasil ditambahkan!";
        } else {
            echo "Gagal menambahkan data: " . $stmt->error;
        }
        $last_id = $stmt->insert_id; 
        return $last_id;
    }

    public function getMemberById($idmember) {
        $sql = "Select * FROM member WHERE idmember = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $idmember);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getMemberName($idmember) {
        $sql = "Select CONCAT(fname, ' ', lname) AS full_name FROM member WHERE idmember = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $idmember);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row ? $row['full_name'] : 'Unknown Member';
    }

    public function updateMemberTeam($idmember, $idteam) {
        $sql = "Update member SET idteam = ? WHERE idmember = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("ii", $idteam, $idmember);
        return $stmt->execute();
    }
}
?>