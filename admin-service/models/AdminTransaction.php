<?php
require_once __DIR__ . '/../config/koneksi.php';

class AdminTransaction {
    private $conn;
    private $campaignConn;

    public function __construct() {
        $db = Database::getInstance();
        $this->conn = $db->getTransactionDB();
        $this->campaignConn = $db->getCampaignDB();
    }

    public function getAllDonations($status = null) {
        $sql = "SELECT * FROM donations";
        if ($status) {
            $status_esc = $this->conn->real_escape_string($status);
            $sql .= " WHERE status = '$status_esc'";
        }
        $sql .= " ORDER BY created_at DESC";

        $result = $this->conn->query($sql);
        $donations = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $campaignId = (int)$row['campaign_id'];
                $campSql = "SELECT title FROM campaigns WHERE id = $campaignId";
                $campRes = $this->campaignConn->query($campSql);
                if ($campRes && $campRes->num_rows > 0) {
                    $row['campaign_title'] = $campRes->fetch_assoc()['title'];
                } else {
                    $row['campaign_title'] = 'Unknown Campaign';
                }
                $donations[] = $row;
            }
        }
        return $donations;
    }

    public function updateStatus($id, $status) {
        $id_esc = $this->conn->real_escape_string($id);
        $status_esc = $this->conn->real_escape_string($status);
        
        // Cek status saat ini
        $check = $this->conn->query("SELECT status FROM donations WHERE id = '$id_esc'");
        if (!$check || $check->num_rows == 0) return false;
        
        $currentStatus = $check->fetch_assoc()['status'];
        if ($currentStatus === $status) return true; // Tidak ada perubahan

        $sql = "UPDATE donations SET status = '$status_esc', updated_at = NOW() WHERE id = '$id_esc'";
        $success = $this->conn->query($sql);
        
        if ($success && $status === 'paid' && $currentStatus === 'pending') {
            $donSql = "SELECT * FROM donations WHERE id = '$id_esc'";
            $donRes = $this->conn->query($donSql);
            if ($donRes && $donRes->num_rows > 0) {
                $don = $donRes->fetch_assoc();
                $treesCount = (int)$don['trees_count'];
                $campaignId = (int)$don['campaign_id'];
                
                // Update Campaign
                $updateCamp = "UPDATE campaigns SET current_trees = current_trees + $treesCount WHERE id = $campaignId";
                $this->campaignConn->query($updateCamp);
                
                // Generate Certificate
                $certNo = 'SP-CERT-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
                $donorName = $this->conn->real_escape_string($don['donor_name']);
                
                $campSql = "SELECT title FROM campaigns WHERE id = $campaignId";
                $campRes = $this->campaignConn->query($campSql);
                $campaignName = $campRes && $campRes->num_rows > 0 ? $this->conn->real_escape_string($campRes->fetch_assoc()['title']) : '';
                
                $issuedAt = date('Y-m-d');
                $sqlCert = "INSERT INTO certificates (certificate_number, donation_id, donor_name, campaign_name, trees_count, issued_at, issued_by) 
                            VALUES ('$certNo', '$id_esc', '$donorName', '$campaignName', $treesCount, '$issuedAt', 'Sodakoh Pohon')";
                $this->conn->query($sqlCert);
            }
        }
        return $success;
    }
}
