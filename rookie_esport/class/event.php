    <?php 
    require_once("parent.php");

    class Event extends ParentClass {
        public function __construct() {
            parent::__construct();
        }

        public function addEvent($arrKolom){
            $sql = "Insert into event (name, date, description) VALUES (?, ?, ?)";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("sss", $arrKolom['name_event'], $arrKolom['date_event'], $arrKolom['description_event']);
            if ($stmt->execute()) {
                echo "Data event berhasil ditambahkan!";
            } else {
                echo "Gagal menambahkan data: " . $stmt->error;
            }
            $last_id = $stmt->insert_id; 
            return $last_id;
        }
        
        public function getEvent($cari = "", $offset = null, $limit = null) {
            $cari_persen = "%" . $cari . "%";
            if (is_null($limit)) {
                $stmt = $this->mysqli->prepare("Select * from event WHERE name LIKE ?");
                $stmt->bind_param("s", $cari_persen);
            } else {
                $stmt = $this->mysqli->prepare("Select * from event WHERE name LIKE ? LIMIT ?, ?");
                $stmt->bind_param("sii", $cari_persen, $offset, $limit);
            }
            $stmt->execute();
            $res = $stmt->get_result(); 
            return $res;
        }   

        public function deleteEvent($arrKolom){
            $sql = "Delete FROM event WHERE idevent = ?";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param('i', $arrKolom['idevent']);
            if ($stmt->execute()) {
                echo "Data event berhasil dihapus!";
            } else {
                echo "Gagal menghapus data: " . $stmt->error;
            }
        }

        public function updateEvent($arrKolom) {
            $sql = "Update event Set name = ?, date = ?, description = ? Where idevent = ?";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("sssi", $arrKolom['name_event'], $arrKolom['date_event'], $arrKolom['description_event'], $arrKolom['idevent']);
            
            if ($stmt->execute()) {
                header("Location: event.php");
                exit();
            } else {
                echo "Failed to execute statement: " . $stmt->error;
            }
        }

        public function selectEvent($arrKolom) {
            $idevent = $arrKolom['idevent']; 
            $sql = "Select * FROM event WHERE idevent = ?";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("i", $idevent);
            $stmt->execute();
            $result = $stmt->get_result();
            
            return $result->fetch_assoc();
        }


        public function getTeamName($idteam) {
            $sql = "SELECT name FROM team WHERE idteam = ?";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("i", $idteam);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            
            return $row ? $row['name'] : null;
        }

        public function getEventTeams($idteam, $search = "", $offset = null, $limit = null) {
            $sql = "
                SELECT e.idevent, e.name, e.date, e.description
                FROM event_teams et
                INNER JOIN event e ON et.idevent = e.idevent
                WHERE et.idteam = ?
            ";
            
            // Tambahkan kondisi pencarian jika ada
            if (!empty($search)) {
                $sql .= " AND e.name LIKE ?";
            }
        
            // Tambahkan LIMIT dan OFFSET
            if (!is_null($limit)) {
                $sql .= " LIMIT ? OFFSET ?";
            }
        
            $stmt = $this->mysqli->prepare($sql);
        
            if (!empty($search) && !is_null($limit)) {
                $searchParam = "%$search%";
                $stmt->bind_param("isis", $idteam, $searchParam, $limit, $offset);
            } else if (!empty($search)) {
                $searchParam = "%$search%";
                $stmt->bind_param("is", $idteam, $searchParam);
            } else if (!is_null($limit)) {
                $stmt->bind_param("iii", $idteam, $limit, $offset);
            } else {
                $stmt->bind_param("i", $idteam);
            }
        
            $stmt->execute();
            $res = $stmt->get_result();
            return $res;
        }

        public function countEventTeams($idteam) {
            $sql = "
                Select COUNT(*) as count
                FROM event_teams
                WHERE idteam = ?
            ";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("i", $idteam);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            
            return $row['count'];
        }

        public function removeTeamFromEvent($idteam, $idevent) {
            $sql = "Delete FROM event_teams WHERE idteam = ? AND idevent = ?";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("ii", $idteam, $idevent);
            return $stmt->execute();
        }

        public function updateTeamEvent($idteam, $old_idevent, $new_idevent) {
            $sql = "UPDATE event_teams SET idevent = ? WHERE idteam = ? AND idevent = ?";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("iii", $new_idevent, $idteam, $old_idevent);
            return $stmt->execute();
        }

        public function getEventDetails($idevent) {
            $sql = "SELECT * FROM event WHERE idevent = ?";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("i", $idevent);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        }

        public function getAllEvents() {
            $sql = "SELECT * FROM event ORDER BY name";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->execute();
            return $stmt->get_result();
        }

        public function getTotalEvents() {
            $sql = "SELECT COUNT(*) as total FROM event";
            $result = $this->mysqli->query($sql);
            $row = $result->fetch_assoc();
            return $row['total'];
        }

        public function displayUpcomingEvents($idteam) {
            $today = date('Y-m-d');
            $sql = "
                SELECT e.idevent, e.name, e.date, e.description
                FROM event_teams et
                INNER JOIN event e ON et.idevent = e.idevent
                WHERE et.idteam = ? AND e.date >= ?
                ORDER BY e.date ASC
            ";

            $stmt = $this->mysqli->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("is", $idteam, $today);
                $stmt->execute();
                $team_events = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                $stmt->close();
                return $team_events;
            } else {
                return [];
            }
        }
        
    }
    ?>