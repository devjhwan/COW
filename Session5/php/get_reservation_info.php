<?php
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    header('Content-Type: application/json');

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "hotel";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
      http_response_code(500);
      echo json_encode(["error" => "Database connection failed."]);
      exit;
    }

    $reservation_code = trim($_POST["reservation_code"]);
    $last_name = trim($_POST["last_name"]);

    $sql = "
      SELECT r.reservation_code, r.country, r.city, r.start_date, r.end_date,
        c.first_name, c.last_name, c.doc_type, c.doc_id
      FROM reservations r
      JOIN clients c ON r.client_id = c.client_id
      WHERE r.reservation_code = '$reservation_code'
      AND c.last_name = '$last_name'
    "; //buah sqlinjection jaja

    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
      $data = $result->fetch_assoc();
      echo json_encode(["data" => $data]);
    } else {
      http_response_code(204);
      exit;
    }

    $conn->close();
  }
?>