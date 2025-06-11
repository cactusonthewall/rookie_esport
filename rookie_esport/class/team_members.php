<?php 
require_once("parent.php");

class TeamMember extends ParentClass {
    public function __construct() {
        parent::__construct();
    }

    public function addTeamMember($teamId, $memberId, $description) {
        $sql = "Insert into team_members (idteam, idmember, description) VALUES (?, ?, ?)";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("iis", $teamId, $memberId, $description);
        return $stmt->execute();
    }

    public function getTeamsByMemberId($memberId) {
        $sql = "Select t.idteam, t.name 
                  FROM team_members tm 
                  JOIN team t ON tm.idteam = t.idteam 
                  WHERE tm.idmember = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param('i', $memberId);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function removeMemberFromTeam($idteam, $idmember) {
        $stmt = $this->mysqli->prepare("Delete FROM team_members WHERE idteam = ? AND idmember = ?");
        $stmt->bind_param('ii', $idteam, $idmember);
        return $stmt->execute();
    }

    public function getMembersByTeamId($teamId, $offset = 0, $limit = 5) {
        $sql = "SELECT m.idmember, m.fname, m.lname 
                FROM team_members tm 
                JOIN member m ON tm.idmember = m.idmember 
                WHERE tm.idteam = ? 
                LIMIT ?, ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("iii", $teamId, $offset, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC); 
    }
    
    public function getTeamMembers($idteam, $cari = "", $offset = null, $limit = null) {
        $cari_persen = "%" . $cari . "%";
    
        if (is_null($limit)) {
            $sql = "
                SELECT m.idmember, m.fname, m.lname, m.username, m.profile
                FROM member m
                JOIN team_members tm ON tm.idmember = m.idmember
                JOIN team t ON t.idteam = tm.idteam
                WHERE t.idteam = ?
                AND (m.fname LIKE ? OR m.lname LIKE ? OR m.username LIKE ?)
            ";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param('ssss', $idteam, $cari_persen, $cari_persen, $cari_persen);
        } else {
            $sql = "
                SELECT m.idmember, m.fname, m.lname, m.username, m.profile
                FROM member m
                JOIN team_members tm ON tm.idmember = m.idmember
                JOIN team t ON t.idteam = tm.idteam
                WHERE t.idteam = ?
                AND (m.fname LIKE ? OR m.lname LIKE ? OR m.username LIKE ?)
                LIMIT ?, ?
            ";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param('ssssii', $idteam, $cari_persen, $cari_persen, $cari_persen, $offset, $limit);
        }
    
        $stmt->execute();
        return $stmt->get_result();
    }    

    public function getJoinProposalsByMemberId($idmember) {
        $sql = "SELECT jp.idjoin_proposal, t.name AS team_name, jp.status
                FROM join_proposal jp
                INNER JOIN team t ON jp.idteam = t.idteam
                WHERE jp.idmember = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $idmember);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getApprovedTeamByMemberId($idmember) {
        $sql = "SELECT t.idteam, t.name FROM team t JOIN join_proposal jp ON jp.idteam = t.idteam WHERE jp.idmember = ? AND jp.status = 'approved'"; 
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param('i', $idmember);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc(); 
    }
    
}
?>