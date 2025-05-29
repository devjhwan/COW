<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  require_once "db_connection.php";

  header('Content-Type: application/json; charset=utf-8');

  $conn = createConnection("world");

  if ($conn === null) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
  }

  try {
    $result = $conn->query("SELECT name, code FROM countries");

    if ($result === false)
      throw new PDOException("Query failed");

    $countries = [];
    while ($row = $result->fetch(PDO::FETCH_ASSOC))
      $countries[] = $row;

    echo json_encode($countries);
  } catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Query failed"]);
  }

  $conn = null;
}
?>
