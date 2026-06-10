<?php
// models/CampaignSubmission.php
// Model untuk mengelola data pengajuan campaign dari user

require_once dirname(__DIR__) . '/config/koneksi.php';

class CampaignSubmission
{
    private $db;
    private $conn;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->conn = $this->db->getConnection();
    }

    public function getAll($status = null, $stage = null, $limit = null)
    {
        $sql = "SELECT * FROM campaign_submissions WHERE 1=1";

        if ($status) {
            $status_esc = $this->conn->real_escape_string($status);
            $sql .= " AND status = '{$status_esc}'";
        }
        
        if ($stage !== null) {
            $stage_esc = (int)$stage;
            $sql .= " AND stage = {$stage_esc}";
        }

        $sql .= " ORDER BY created_at DESC";

        if ($limit) {
            $limit_esc = (int)$limit;
            $sql .= " LIMIT {$limit_esc}";
        }

        $result = $this->conn->query($sql);

        $submissions = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $submissions[] = $row;
            }
        }

        return $submissions;
    }
    
    /**
     * Update data pengajuan untuk Tahap 2
     */
    public function updateStage2Data($id, $data)
    {
        $id_esc = $this->conn->real_escape_string($id);
        
        $price = isset($data['price_per_tree']) ? (float)$data['price_per_tree'] : 0;
        $desc = isset($data['long_description']) ? "'" . $this->conn->real_escape_string($data['long_description']) . "'" : "NULL";
        $map = isset($data['map_url']) ? "'" . $this->conn->real_escape_string($data['map_url']) . "'" : "NULL";
        $deadline = isset($data['deadline']) ? "'" . $this->conn->real_escape_string($data['deadline']) . "'" : "NULL";
        $cat = isset($data['category']) ? "'" . $this->conn->real_escape_string($data['category']) . "'" : "'Umum'";
        $partner = isset($data['partner']) ? "'" . $this->conn->real_escape_string($data['partner']) . "'" : "NULL";
        $benefits = isset($data['benefits_json']) ? "'" . $this->conn->real_escape_string($data['benefits_json']) . "'" : "NULL";
        
        $sql = "UPDATE campaign_submissions SET 
                stage = 2,
                status = 'pending',
                price_per_tree = {$price},
                long_description = {$desc},
                map_url = {$map},
                deadline = {$deadline},
                category = {$cat},
                partner = {$partner},
                benefits_json = {$benefits}
                WHERE id = '{$id_esc}'";
                
        return $this->conn->query($sql);
    }
    
    /**
     * Update data pengajuan untuk Tahap 1
     */
    public function updateStage1Data($id, $data)
    {
        $id_esc = $this->conn->real_escape_string($id);
        
        $title = isset($data['title']) ? "'" . $this->conn->real_escape_string($data['title']) . "'" : "title";
        $description = isset($data['description']) ? "'" . $this->conn->real_escape_string($data['description']) . "'" : "description";
        $location = isset($data['location']) ? "'" . $this->conn->real_escape_string($data['location']) . "'" : "location";
        $tree_type = isset($data['tree_type']) ? "'" . $this->conn->real_escape_string($data['tree_type']) . "'" : "tree_type";
        $target_trees = isset($data['target_trees']) ? (int)$data['target_trees'] : "target_trees";
        $submitter_name = isset($data['submitter_name']) ? "'" . $this->conn->real_escape_string($data['submitter_name']) . "'" : "submitter_name";
        $submitter_phone = isset($data['submitter_phone']) ? "'" . $this->conn->real_escape_string($data['submitter_phone']) . "'" : "submitter_phone";
        $organization_name = isset($data['organization_name']) ? "'" . $this->conn->real_escape_string($data['organization_name']) . "'" : "organization_name";
        
        // If there's a new image
        $image_sql = "";
        if (isset($data['image'])) {
            $image = $this->conn->real_escape_string($data['image']);
            $image_sql = ", image = '{$image}'";
        }
        
        $sql = "UPDATE campaign_submissions SET 
                status = 'pending',
                title = {$title},
                description = {$description},
                location = {$location},
                tree_type = {$tree_type},
                target_trees = {$target_trees},
                submitter_name = {$submitter_name},
                submitter_phone = {$submitter_phone},
                organization_name = {$organization_name}
                {$image_sql}
                WHERE id = '{$id_esc}'";
                
        return $this->conn->query($sql);
    }

    /**
     * Mendapatkan pengajuan campaign by ID
     */
    public function getById($id)
    {
        $id_esc = $this->conn->real_escape_string($id);
        $sql = "SELECT * FROM campaign_submissions WHERE id = '{$id_esc}' LIMIT 1";

        $result = $this->conn->query($sql);

        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }

        return null;
    }

    /**
     * Update status pengajuan
     */
    public function updateStatus($id, $status)
    {
        $id_esc = $this->conn->real_escape_string($id);
        $status_esc = $this->conn->real_escape_string($status);

        $sql = "UPDATE campaign_submissions SET status = '{$status_esc}' WHERE id = '{$id_esc}'";

        return $this->conn->query($sql);
    }

    /**
     * Hapus pengajuan campaign
     */
    public function delete($id)
    {
        $id_esc = $this->conn->real_escape_string($id);
        $sql = "DELETE FROM campaign_submissions WHERE id = '{$id_esc}'";

        return $this->conn->query($sql);
    }

    /**
     * Mendapatkan statistik pengajuan campaign
     */
    public function getStats()
    {
        $stats = [];

        // Total pengajuan
        $result = $this->conn->query("SELECT COUNT(*) as total FROM campaign_submissions");
        $stats['total_submissions'] = $result->fetch_assoc()['total'];

        // Menunggu
        $result = $this->conn->query("SELECT COUNT(*) as total FROM campaign_submissions WHERE status = 'pending'");
        $stats['pending_submissions'] = $result->fetch_assoc()['total'];

        // Disetujui
        $result = $this->conn->query("SELECT COUNT(*) as total FROM campaign_submissions WHERE status = 'approved'");
        $stats['approved_submissions'] = $result->fetch_assoc()['total'];

        // Ditolak
        $result = $this->conn->query("SELECT COUNT(*) as total FROM campaign_submissions WHERE status = 'rejected'");
        $stats['rejected_submissions'] = $result->fetch_assoc()['total'];

        return $stats;
    }
}
?>
