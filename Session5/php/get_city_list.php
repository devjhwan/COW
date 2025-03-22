<?php
header('Content-Type: application/json; charset=utf-8');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "world";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  http_response_code(500);
  echo json_encode(["error" => "Database connection failed"]);
  exit;
}

$conn->set_charset("utf8");

$country_code = isset($_GET['country_code']) ? $_GET['country_code'] : "";

if (empty($country_code)) {
  http_response_code(400);
  echo json_encode(["error" => "Missing country_code"]);
  $conn->close();
  exit;
}

$country_code = $conn->real_escape_string($country_code);

$sql = "SELECT name FROM cities WHERE country_code = '$country_code'";
$result = $conn->query($sql);

$cities = [];

if ($result) {
  while ($row = $result->fetch_assoc()) {
    $cities[] = $row['name'];
  }
} else {
  http_response_code(500);
  echo json_encode(["error" => "Query failed"]);
  $conn->close();
  exit;
}

$conn->close();
echo json_encode($cities);
