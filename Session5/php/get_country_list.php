<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  header('Content-Type: application/json; charset=utf-8');

  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "world";
  
  $conn = new mysqli($servername, $username, $password, $dbname);
  
  if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    $conn->close();
    exit;
  }
  
  $conn->set_charset("utf8");
  
  $countries = [];
  $sql = "SELECT name, code FROM countries";
  $result = $conn->query($sql);
  if ($result) {
    while ($row = $result->fetch_assoc()) {
      $countries[] = $row;
    }
  }
  else {
    http_response_code(500);
    echo json_encode(["error" => "Query failed"]);
    $conn->close();
    exit;
  }
  
  $conn->close();
  echo json_encode($countries);  
}
?>