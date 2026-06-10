<?php
require_once __DIR__ . '/../config/koneksi.php';

class AdminSubmission {
    private $conn;

    public function __construct() {
        $db = Database::getInstance();
        $this->conn = $db->getCampaignDB();
    }

    public function getAll() {
        $sql = "SELECT * FROM campaign_submissions ORDER BY created_at DESC";
        $result = $this->conn->query($sql);
        $submissions = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $submissions[] = $row;
            }
        }
        return $submissions;
    }

    public function updateStatus($id, $status) {
        $id_esc = $this->conn->real_escape_string($id);
        $status_esc = $this->conn->real_escape_string($status);
        $sql = "UPDATE campaign_submissions SET status = '$status_esc' WHERE id = '$id_esc'";
        return $this->conn->query($sql);
    }
    
    // Add campaign creation logic when approved
    public function approveToCampaign($id) {
        $id_esc = $this->conn->real_escape_string($id);
        $sql = "SELECT * FROM campaign_submissions WHERE id = '$id_esc'";
        $res = $this->conn->query($sql);
        if ($res && $res->num_rows > 0) {
            $sub = $res->fetch_assoc();
            
            // Create campaign
            $title = $this->conn->real_escape_string($sub['title']);
            $desc = $this->conn->real_escape_string($sub['description']);
            $loc = $this->conn->real_escape_string($sub['location']);
            $treeType = $this->conn->real_escape_string($sub['tree_type']);
            $target = (int)$sub['target_trees'];
            $price = (float)$sub['price_per_tree'];
            $longDesc = $this->conn->real_escape_string($sub['long_description']);
            $mapUrl = $this->conn->real_escape_string($sub['map_url']);
            $deadline = $this->conn->real_escape_string($sub['deadline']);
            $cat = $this->conn->real_escape_string($sub['category']);
            $partner = $this->conn->real_escape_string($sub['partner']);
            $image = $this->conn->real_escape_string($sub['image']);
            
            $insert = "INSERT INTO campaigns (title, description, location, tree_type, target_trees, price_per_tree, 
                        long_description, map_url, deadline, category, partner, image, status) 
                       VALUES ('$title', '$desc', '$loc', '$treeType', $target, $price, 
                        '$longDesc', '$mapUrl', '$deadline', '$cat', '$partner', '$image', 'active')";
            
            if ($this->conn->query($insert)) {
                $campId = $this->conn->insert_id;
                
                // Parse and insert benefits
                $benefits = json_decode($sub['benefits_json'], true);
                if (is_array($benefits)) {
                    foreach ($benefits as $b) {
                        $b_esc = $this->conn->real_escape_string($b);
                        $this->conn->query("INSERT INTO campaign_benefits (campaign_id, benefit) VALUES ($campId, '$b_esc')");
                    }
                }
                
                // Update submission status
                $this->updateStatus($id, 'approved');
                return true;
            }
        }
        return false;
    }
}
