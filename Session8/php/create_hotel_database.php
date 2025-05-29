<?php
require_once "db_connection.php";

$dbname = "hotel";

$conn = createConnection();

if ($conn === null) {
  die("Connection failed");
}

try {
  $conn->exec("CREATE DATABASE IF NOT EXISTS $dbname");

  $conn->exec("USE $dbname");

  $conn->exec("
    CREATE TABLE IF NOT EXISTS users (
      id INT AUTO_INCREMENT PRIMARY KEY,
      user_id VARCHAR(20) NOT NULL UNIQUE,
      password VARCHAR(30) NOT NULL
    )
  ");

  $conn->exec("
    CREATE TABLE IF NOT EXISTS clients (
      client_id INT AUTO_INCREMENT PRIMARY KEY,
      first_name VARCHAR(50) NOT NULL,
      last_name VARCHAR(50) NOT NULL,
      doc_type ENUM('DNI', 'NIE', 'PASSPORT') NOT NULL,
      doc_id VARCHAR(20) NOT NULL,
      user_id INT,
      FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
      UNIQUE KEY unique_client_per_user (user_id, doc_type, doc_id)
    )
  ");

  $conn->exec("
    CREATE TABLE IF NOT EXISTS reservations (
      id INT AUTO_INCREMENT PRIMARY KEY,
      client_id INT NOT NULL,
      reservation_code VARCHAR(8) UNIQUE NOT NULL,
      country VARCHAR(100) NOT NULL,
      city VARCHAR(100) NOT NULL,
      start_date DATE NOT NULL,
      end_date DATE NOT NULL,
      FOREIGN KEY (client_id) REFERENCES clients(client_id) ON DELETE CASCADE
    )
  ");

} catch (PDOException $e) {
  die("Error: " . $e->getMessage());
}

$conn = null;
?>
