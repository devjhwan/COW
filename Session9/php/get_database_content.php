<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
  http_response_code(405);
  echo json_encode(["error" => "Only GET requests are allowed."]);
  exit;
}

include_once 'create_hotel_database.php';
require_once "db_connection.php";

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

$conn = createConnection($dbname);
if ($conn === null) {
  http_response_code(500);
  echo json_encode(["error" => "Database connection failed"]);
  exit;
}

// 테이블 목록
$tables = [];
try {
  $result = $conn->query("SHOW TABLES");
  while ($row = $result->fetch(PDO::FETCH_NUM))
    $tables[] = $row[0];
} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode(["error" => "Failed to fetch table list"]);
  $conn = null;
  exit;
}

if (empty($selected_table) && count($tables) > 0)
  $selected_table = $tables[0];

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
  while ($row = $result->fetch(PDO::FETCH_ASSOC))
    $response['columns'][] = $row['Field'];

  $result = $conn->query("SELECT * FROM `$selected_table` LIMIT $limit OFFSET $offset");
  while ($row = $result->fetch(PDO::FETCH_ASSOC))
    $response['rows'][] = $row;

  $result = $conn->query("SELECT COUNT(*) AS total FROM `$selected_table`");
  $row = $result->fetch(PDO::FETCH_ASSOC);
  $total_rows = isset($row['total']) ? (int)$row['total'] : 0;
  if (($offset + $limit) < $total_rows)
      $response['has_next_page'] = true;

}

$conn = null;
echo json_encode($response);
