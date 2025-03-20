<!--
###############################################################################
#                                                                             #
#   Author: JungHwan Lee                                                      #
#   Submission Deadline: 31th March                                           #
#   Niub: 20467554                                                            #
#                                                                             #
###############################################################################
-->

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hotel";

$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === FALSE) {
  die("Error creating database: " . $conn->error);
}

$conn->select_db($dbname);

$sql = "CREATE TABLE IF NOT EXISTS clients (
  client_id INT AUTO_INCREMENT PRIMARY KEY,
  first_name VARCHAR(50) NOT NULL,
  last_name VARCHAR(50) NOT NULL,
  doc_type ENUM('DNI', 'NIE', 'PASSPORT') NOT NULL,
  doc_id VARCHAR(20) NOT NULL UNIQUE
)";
if ($conn->query($sql) === FALSE) {
  die("Error creating table 'clients': " . $conn->error);
}

$sql = "CREATE TABLE IF NOT EXISTS reservations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  client_id INT NOT NULL,
  city VARCHAR(100) NOT NULL,
  FOREIGN KEY (client_id) REFERENCES clients(client_id) ON DELETE CASCADE
)";
if ($conn->query($sql) === FALSE) {
  die("Error creating table 'reservations': " . $conn->error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $first_name = trim($_POST["first_name"]);
  $last_name = trim($_POST["last_name"]);
  $doc_type = trim($_POST["doc_type"]);
  $doc_id = strtoupper(trim($_POST["doc_id"]));
  $city = trim($_POST["city"]);

  $sql = "SELECT client_id FROM clients WHERE doc_type = '$doc_type' AND doc_id = '$doc_id'";
  $result = $conn->query($sql);

  if ($result->num_rows == 0) {
    $sql = "INSERT INTO clients (first_name, last_name, doc_type, doc_id) VALUES ('$first_name', '$last_name', '$doc_type', '$doc_id')";
    if ($conn->query($sql) === FALSE) {
      die("Error inserting client: " . $conn->error);
    }
    $client_id = $conn->insert_id;
  } else {
    $row = $result->fetch_assoc();
    $client_id = $row["client_id"];
  }

  $sql = "INSERT INTO reservations (client_id, city) VALUES ('$client_id', '$city')";
  if ($conn->query($sql) === FALSE) {
    die("Error inserting reservation: " . $conn->error);
  }
}

$conn->close();
?>
