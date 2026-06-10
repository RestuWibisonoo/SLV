<?php
// admin-service/models/AdminCampaign.php

require_once dirname(__DIR__) . '/config/koneksi.php';

class AdminCampaign
{
    private $db;
    private $conn;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->conn = $this->db->getCampaignDB();
    }

    public function getAll($status = null)
    {
        $sql = "SELECT * FROM campaigns WHERE 1=1";
        if ($status) {
            $status_esc = $this->conn->real_escape_string($status);
            $sql .= " AND status = '{$status_esc}'";
        }
        $sql .= " ORDER BY created_at DESC";
        $result = $this->conn->query($sql);
        $campaigns = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $campaigns[] = $row;
            }
        }
        return $campaigns;
    }

    public function getById($id)
    {
        $id_esc = $this->conn->real_escape_string($id);
        $sql = "SELECT * FROM campaigns WHERE id = '{$id_esc}' LIMIT 1";
        $result = $this->conn->query($sql);
        if ($result && $result->num_rows > 0) {
            $campaign = $result->fetch_assoc();
            // Get benefits
            $campaign['benefits'] = $this->getBenefits($id);
            return $campaign;
        }
        return null;
    }

    public function create($data)
    {
        $title = $this->conn->real_escape_string($data['title']);
        $description = $this->conn->real_escape_string($data['description']);
        $long_description = isset($data['long_description']) ? $this->conn->real_escape_string($data['long_description']) : '';
        $location = $this->conn->real_escape_string($data['location']);
        $tree_type = $this->conn->real_escape_string($data['tree_type']);
        $price_per_tree = (float)$data['price_per_tree'];
        $target_trees = (int)$data['target_trees'];
        $current_trees = isset($data['current_trees']) ? (int)$data['current_trees'] : 0;
        $planted_trees = isset($data['planted_trees']) ? (int)$data['planted_trees'] : 0;
        $image = isset($data['image']) ? $this->conn->real_escape_string($data['image']) : '';
        $status = isset($data['status']) ? $this->conn->real_escape_string($data['status']) : 'active';
        $partner = isset($data['partner']) ? $this->conn->real_escape_string($data['partner']) : '';
        $map_url = isset($data['map_url']) ? $this->conn->real_escape_string($data['map_url']) : '';
        $deadline = $this->conn->real_escape_string($data['deadline']);
        $created_at = date('Y-m-d H:i:s');

        $sql = "INSERT INTO campaigns (
                    title, description, long_description, location, tree_type, 
                    price_per_tree, target_trees, current_trees, planted_trees, 
                    image, map_url, status, partner, deadline, created_at
                ) VALUES (
                    '{$title}', '{$description}', '{$long_description}', '{$location}', '{$tree_type}',
                    {$price_per_tree}, {$target_trees}, {$current_trees}, {$planted_trees},
                    '{$image}', '{$map_url}', '{$status}', '{$partner}', '{$deadline}', '{$created_at}'
                )";

        if ($this->conn->query($sql)) {
            return $this->conn->insert_id;
        }
        return false;
    }

    public function update($id, $data)
    {
        if (empty($data)) return false;

        $sets = [];
        foreach ($data as $key => $value) {
            $key_esc = $this->conn->real_escape_string($key);
            $value_esc = $this->conn->real_escape_string($value);
            $sets[] = "`{$key_esc}` = '{$value_esc}'";
        }
        $sets[] = "`updated_at` = NOW()";

        $set_string = implode(", ", $sets);
        $id_int = (int)$id;

        $sql = "UPDATE campaigns SET {$set_string} WHERE id = {$id_int}";
        return $this->conn->query($sql);
    }

    public function delete($id)
    {
        $id_int = (int)$id;
        
        $plantings_result = $this->conn->query("SELECT id FROM plantings WHERE campaign_id = {$id_int}");
        if ($plantings_result && $plantings_result->num_rows > 0) {
            while ($p = $plantings_result->fetch_assoc()) {
                $this->conn->query("DELETE FROM planting_gallery WHERE planting_id = {$p['id']}");
            }
        }
        $this->conn->query("DELETE FROM plantings WHERE campaign_id = {$id_int}");
        $this->conn->query("DELETE FROM donations WHERE campaign_id = {$id_int}");
        $this->conn->query("DELETE FROM campaign_benefits WHERE campaign_id = {$id_int}");
        $this->conn->query("DELETE FROM campaign_gallery WHERE campaign_id = {$id_int}");
        
        $sql = "DELETE FROM campaigns WHERE id = {$id_int}";
        return $this->conn->query($sql);
    }

    public function getBenefits($campaign_id)
    {
        $id_esc = $this->conn->real_escape_string($campaign_id);
        $sql = "SELECT * FROM campaign_benefits WHERE campaign_id = '{$id_esc}' ORDER BY id ASC";
        $result = $this->conn->query($sql);
        $benefits = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $benefits[] = $row['benefit'];
            }
        }
        return $benefits;
    }

    public function clearBenefits($campaign_id)
    {
        $id_esc = $this->conn->real_escape_string($campaign_id);
        $this->conn->query("DELETE FROM campaign_benefits WHERE campaign_id = '{$id_esc}'");
    }

    public function addBenefit($campaign_id, $benefit)
    {
        $campaign_id_esc = $this->conn->real_escape_string($campaign_id);
        $benefit_esc = $this->conn->real_escape_string($benefit);
        $sql = "INSERT INTO campaign_benefits (campaign_id, benefit) VALUES ('{$campaign_id_esc}', '{$benefit_esc}')";
        return $this->conn->query($sql);
    }

    public function getStats()
    {
        $stats = [];
        $result = $this->conn->query("SELECT COUNT(*) as total FROM campaigns");
        $stats['total_campaigns'] = $result->fetch_assoc()['total'];

        $result = $this->conn->query("SELECT SUM(current_trees) as total FROM campaigns");
        $stats['total_trees_collected'] = $result->fetch_assoc()['total'] ?? 0;

        $result = $this->conn->query("SELECT SUM(planted_trees) as total FROM campaigns");
        $stats['total_trees_planted'] = $result->fetch_assoc()['total'] ?? 0;

        $result = $this->conn->query("SELECT SUM(target_trees) as total FROM campaigns");
        $stats['total_target_trees'] = $result->fetch_assoc()['total'] ?? 0;

        $result = $this->conn->query("SELECT COUNT(*) as total FROM campaigns WHERE status = 'active'");
        $stats['active_campaigns'] = $result->fetch_assoc()['total'];

        $result = $this->conn->query("SELECT SUM(amount) as total FROM donations WHERE status = 'paid'");
        $stats['total_donations'] = $result->fetch_assoc()['total'] ?? 0;

        return $stats;
    }

    public function uploadImage($file)
    {
        $target_dir = UPLOAD_PATH . 'campaigns/';
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $file_name = uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $file_name;

        $check = getimagesize($file['tmp_name']);
        if ($check === false) return false;
        
        $allowed_types = ['jpg', 'jpeg', 'png', 'webp'];
        if (!in_array($file_extension, $allowed_types)) return false;

        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            return 'uploads/campaigns/' . $file_name;
        }
        return false;
    }
}
