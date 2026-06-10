<?php
// admin-service/config/koneksi.php

class Database {
    private static $instance = null;
    private $userConn;
    private $campaignConn;
    private $transactionConn;

    private function __construct() {
        // Load .env
        $envFile = dirname(__DIR__) . '/.env';
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos(trim($line), '#') === 0) continue;
                list($name, $value) = explode('=', $line, 2);
                $_ENV[trim($name)] = trim($value);
            }
        }

        // Connection for User DB
        $userHost = $_ENV['USER_DB_HOST'] ?? 'localhost';
        $userUser = $_ENV['USER_DB_USER'] ?? 'root';
        $userPass = $_ENV['USER_DB_PASS'] ?? '';
        $userName = $_ENV['USER_DB_NAME'] ?? 'db_user';

        $this->userConn = new mysqli($userHost, $userUser, $userPass, $userName);
        if ($this->userConn->connect_error) {
            die("User DB Connection failed: " . $this->userConn->connect_error);
        }

        // Connection for Campaign DB
        $campaignHost = $_ENV['CAMPAIGN_DB_HOST'] ?? 'localhost';
        $campaignUser = $_ENV['CAMPAIGN_DB_USER'] ?? 'root';
        $campaignPass = $_ENV['CAMPAIGN_DB_PASS'] ?? '';
        $campaignName = $_ENV['CAMPAIGN_DB_NAME'] ?? 'db_campaign';

        $this->campaignConn = new mysqli($campaignHost, $campaignUser, $campaignPass, $campaignName);
        if ($this->campaignConn->connect_error) {
            die("Campaign DB Connection failed: " . $this->campaignConn->connect_error);
        }
        
        // Connection for Transaction DB
        $transactionHost = $_ENV['TRANSACTION_DB_HOST'] ?? 'localhost';
        $transactionUser = $_ENV['TRANSACTION_DB_USER'] ?? 'root';
        $transactionPass = $_ENV['TRANSACTION_DB_PASS'] ?? '';
        $transactionName = $_ENV['TRANSACTION_DB_NAME'] ?? 'db_transaction';

        $this->transactionConn = new mysqli($transactionHost, $transactionUser, $transactionPass, $transactionName);
        if ($this->transactionConn->connect_error) {
            die("Transaction DB Connection failed: " . $this->transactionConn->connect_error);
        }
        
        if (!defined('UPLOAD_PATH')) {
            define('UPLOAD_PATH', $_ENV['UPLOAD_PATH'] ?? '/var/www/html/uploads/');
        }
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getUserDB() {
        return $this->userConn;
    }

    public function getCampaignDB() {
        return $this->campaignConn;
    }

    public function getTransactionDB() {
        return $this->transactionConn;
    }
}
