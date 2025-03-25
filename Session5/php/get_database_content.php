<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
  http_response_code(405); // Method Not Allowed
  echo json_encode(["error" => "Only GET requests are allowed."]);
  exit;
}

include 'create_hotel_database.php';

$servername = "localhost";
$username = "root";
$password = "";

if (!isset($_GET['database'])) {
  http_response_code(400);
  echo json_encode(["error" => "Database not selected."]);
  exit;
}

$dbname = $_GET['database'];
$selected_table = isset($_GET['table']) ? $_GET['table'] : "";
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 15;
$offset = ($page - 1) * $limit;

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  http_response_code(500);
  echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
  exit;
}

$conn->set_charset("utf8");

$tables = [];
$result = $conn->query("SHOW TABLES");
if ($result) {
  while ($row = $result->fetch_array()) {
    $tables[] = $row[0];
  }
}

if (empty($selected_table) && count($tables) > 0) {
  $selected_table = $tables[0];
}

$response = [
  "database" => $dbname,
  "tables" => $tables,
  "selected_table" => $selected_table,
  "page" => $page,
  "columns" => [],
  "rows" => [],
  "has_next_page" => false
];

if (!empty($selected_table)) {
  $result = $conn->query("SHOW COLUMNS FROM `$selected_table`");
  if ($result) {
    while ($row = $result->fetch_assoc()) {
      $response['columns'][] = $row['Field'];
    }
  }


  $result = $conn->query("SELECT * FROM `$selected_table` LIMIT $limit OFFSET $offset");
  if ($result) {
    while ($row = $result->fetch_assoc()) {
      $response['rows'][] = $row;
    }
  }

  $result = $conn->query("SELECT COUNT(*) AS total FROM `$selected_table`");
  if ($result) {
    $total_rows = (int)$result->fetch_assoc()['total'];
    if (($offset + $limit) < $total_rows) {
      $response['has_next_page'] = true;
    }
  }
}

$conn->close();
echo json_encode($response);
