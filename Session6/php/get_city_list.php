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

  $country_code = isset($_GET['country_code']) ? $_GET['country_code'] : "";

  if (empty($country_code)) {
    http_response_code(400);
    echo json_encode(["error" => "Missing country_code"]);
    $conn = null;
    exit;
  }

  try {
    $sql = "SELECT name FROM cities WHERE country_code = '$country_code'";

    $result = $conn->query($sql);
    if ($result === false)
      throw new PDOException("Query failed");

    $cities = [];
    while ($row = $result->fetch(PDO::FETCH_ASSOC))
      $cities[] = $row['name'];

    echo json_encode($cities);
  } catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Query failed"]);
  }

  $conn = null;
}
?>
