<?php 
require_once("parent.php");

class Proposal extends ParentClass {
	public function __construct() {
        parent::__construct();
    }

    public function getProposalList() {
        $sql = "Select jp.idjoin_proposal, m.fname, t.name, jp.description, jp.status 
                FROM join_proposal jp
                INNER JOIN member m ON jp.idmember = m.idmember
                INNER JOIN team t ON jp.idteam = t.idteam
                ORDER BY jp.idjoin_proposal"; 
        $stmt = $this->mysqli->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }

    public function addProposal($memberId, $teamId, $description, $status) {
        $sql = "Insert into join_proposal (idmember, idteam, description, status) values (?, ?, ?, ?)";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("iiss", $memberId, $teamId, $description, $status);
        $result = $stmt->execute();
        return $result;
    }

    public function getProposalDetails($idjoin_proposal) {
        $sql = "Select idmember, idteam, description from join_proposal where idjoin_proposal = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $idjoin_proposal);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getProposalById($idjoin_proposal) {
        $sql = "Select * from join_proposal where idjoin_proposal = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $idjoin_proposal);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function deleteProposal($idjoin_proposal) {
        $sql = "Delete from join_proposal where idjoin_proposal = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $idjoin_proposal);
        $stmt->execute();
    }

    public function updateProposalStatus($idjoin_proposal, $status) {
        $sql = "Update join_proposal set status = ? where idjoin_proposal = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("si", $status, $idjoin_proposal);
        return $stmt->execute();
    }

    public function approveProposal($proposalId) {
        $sql = "Update join_proposal set status = 'approved' where idjoin_proposal = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $proposalId);
        $stmt->execute();
    }

    public function getMemberStatus($idmember) {
        $sql = "Select status FROM join_proposal WHERE idmember = ? ORDER BY idjoin_proposal DESC LIMIT 1";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param('i', $idmember);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            return $row['status'];
        }

        return 'none';
    }
    public function getProposal($cari = "", $offset = null, $limit = null) {
    $cari_persen = "%" . $cari . "%";
    if (is_null($limit)) {
        $stmt = $this->mysqli->prepare("Select jp.idjoin_proposal, m.fname, t.name AS team_name, jp.description, jp.status
            FROM join_proposal jp INNER JOIN member m ON jp.idmember = m.idmember 
            INNER JOIN team t ON jp.idteam = t.idteam WHERE jp.status = 'waiting' 
            AND (t.name LIKE ? OR jp.description LIKE ?)");
        $stmt->bind_param("ss", $cari_persen, $cari_persen);
    } else {
        $stmt = $this->mysqli->prepare("Select jp.idjoin_proposal, m.fname, t.name AS team_name, jp.description, jp.status
        FROM join_proposal jp INNER JOIN member m ON jp.idmember = m.idmember
        INNER JOIN team t ON jp.idteam = t.idteam WHERE jp.status = 'waiting'
        AND (t.name LIKE ? OR jp.description LIKE ?) LIMIT ?, ?");
        $stmt->bind_param("ssii", $cari_persen, $cari_persen, $offset, $limit);
    }
    $stmt->execute();
    $res = $stmt->get_result(); 
    return $res;
    }

    public function getMemberProposalStatus($memberId) {
        $sql = "SELECT status FROM join_proposal 
              JOIN team ON join_proposal.idteam = team.idteam 
              WHERE idmember = 15 AND status = 'waiting' LIMIT 1";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $idmember);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function deleteOtherProposals($memberId, $acceptedTeamId) {
        $sql = "DELETE FROM join_proposal WHERE idmember = ? AND idteam != ? AND status = 'waiting'";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("ii", $memberId, $acceptedTeamId);
        return $stmt->execute();
    }
    
    public function getTotalProposals() {
        $sql = "SELECT COUNT(*) as total FROM join_proposal WHERE status = 'waiting'";
        $result = $this->mysqli->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'];
    }
}
?>