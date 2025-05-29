<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  require_once "db_connection.php";

  header('Content-Type: application/json');

  $conn = createConnection("hotel");

  if ($conn === null) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed."]);
    exit;
  }

  $reservation_code = isset($_POST["reservation_code"]) ? trim($_POST["reservation_code"]) : '';
  $last_name = isset($_POST["last_name"]) ? trim($_POST["last_name"]) : '';

  if ($reservation_code === '' || $last_name === '') {
    http_response_code(400);
    echo json_encode(["error" => "Missing input values."]);
    $conn = null;
    exit;
  }

  $reservation_code = addslashes($reservation_code);
  $last_name = addslashes($last_name);

  $sql = "
    SELECT r.reservation_code, r.country, r.city, r.start_date, r.end_date,
      c.first_name, c.last_name, c.doc_type, c.doc_id
    FROM reservations r
    JOIN clients c ON r.client_id = c.client_id
    WHERE r.reservation_code = '$reservation_code'
    AND c.last_name = '$last_name'
  ";

  try {
    $result = $conn->query($sql);
    $row = $result !== false ? $result->fetch(PDO::FETCH_ASSOC) : false;

    if ($row) 
      echo json_encode(["data" => $row]);
    else
      http_response_code(204);
  } catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Query failed."]);
  }

  $conn = null;
}
?>
