<?php
require_once __DIR__ . '/../config/koneksi.php';

class AdminPlanting {
    private $conn;

    public function __construct() {
        $db = Database::getInstance();
        $this->conn = $db->getCampaignDB();
    }

    public function getAll() {
        $sql = "SELECT p.*, c.title as campaign_name 
                FROM plantings p 
                LEFT JOIN campaigns c ON p.campaign_id = c.id 
                ORDER BY p.planting_date DESC";
        $result = $this->conn->query($sql);
        $plantings = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $plantings[] = $row;
            }
        }
        return $plantings;
    }

    public function getById($id) {
        $id_esc = $this->conn->real_escape_string($id);
        $sql = "SELECT p.*, c.title as campaign_name 
                FROM plantings p 
                LEFT JOIN campaigns c ON p.campaign_id = c.id 
                WHERE p.id = '$id_esc' LIMIT 1";
        $result = $this->conn->query($sql);
        if ($result && $result->num_rows > 0) {
            $planting = $result->fetch_assoc();
            
            $planting['gallery'] = [];
            $galSql = "SELECT * FROM planting_gallery WHERE planting_id = '$id_esc'";
            $galRes = $this->conn->query($galSql);
            if ($galRes && $galRes->num_rows > 0) {
                while ($g = $galRes->fetch_assoc()) {
                    $planting['gallery'][] = $g;
                }
            }
            return $planting;
        }
        return null;
    }

    public function create($data) {
        $campaign_id = (int)$data['campaign_id'];
        $location = $this->conn->real_escape_string($data['location']);
        $trees_planted = (int)$data['trees_planted'];
        $planting_date = $this->conn->real_escape_string($data['planting_date']);
        $volunteers = isset($data['volunteers']) ? (int)$data['volunteers'] : 0;
        $coordinator = isset($data['coordinator']) ? $this->conn->real_escape_string($data['coordinator']) : '';
        $description = isset($data['description']) ? $this->conn->real_escape_string($data['description']) : '';
        $status = strtotime($planting_date) <= time() ? 'completed' : 'scheduled';
        $image = isset($data['image']) ? $this->conn->real_escape_string($data['image']) : '';

        $sql = "INSERT INTO plantings (campaign_id, location, trees_planted, planting_date, volunteers, coordinator, description, status, image) 
                VALUES ($campaign_id, '$location', $trees_planted, '$planting_date', $volunteers, '$coordinator', '$description', '$status', '$image')";
        if ($this->conn->query($sql)) {
            $planting_id = $this->conn->insert_id;
            
            if ($status === 'completed') {
                $this->conn->query("UPDATE campaigns SET planted_trees = planted_trees + $trees_planted WHERE id = $campaign_id");
            }
            return $planting_id;
        }
        return false;
    }

    public function update($id, $data) {
        $id_esc = $this->conn->real_escape_string($id);
        
        $campaign_id = (int)$data['campaign_id'];
        $location = $this->conn->real_escape_string($data['location']);
        $trees_planted = (int)$data['trees_planted'];
        $planting_date = $this->conn->real_escape_string($data['planting_date']);
        $volunteers = isset($data['volunteers']) ? (int)$data['volunteers'] : 0;
        $coordinator = isset($data['coordinator']) ? $this->conn->real_escape_string($data['coordinator']) : '';
        $description = isset($data['description']) ? $this->conn->real_escape_string($data['description']) : '';
        $status = strtotime($planting_date) <= time() ? 'completed' : 'scheduled';
        
        $sql = "UPDATE plantings SET 
                campaign_id = $campaign_id,
                location = '$location',
                trees_planted = $trees_planted,
                planting_date = '$planting_date',
                volunteers = $volunteers,
                coordinator = '$coordinator',
                description = '$description',
                status = '$status'";
        
        if (isset($data['image'])) {
            $image = $this->conn->real_escape_string($data['image']);
            $sql .= ", image = '$image'";
        }
        
        $sql .= " WHERE id = '$id_esc'";
        return $this->conn->query($sql);
    }

    public function delete($id) {
        $id_esc = $this->conn->real_escape_string($id);
        return $this->conn->query("DELETE FROM plantings WHERE id = '$id_esc'");
    }

    public function uploadImage($file) {
        $target_dir = UPLOAD_PATH . 'plantings/';
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $file_name = uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $file_name;
        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            return 'uploads/plantings/' . $file_name;
        }
        return false;
    }
}
