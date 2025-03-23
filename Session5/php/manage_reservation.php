<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  include 'create_hotel_database.php';

  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "hotel";

  $conn = new mysqli($servername, $username, $password, $dbname);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $first_name = trim($_POST["first_name"]);
  $last_name = trim($_POST["last_name"]);
  $doc_type = trim($_POST["doc_type"]);
  $doc_id = strtoupper(trim($_POST["doc_id"]));
  $city = trim($_POST["city"]);
  $country = trim($_POST["country"]);
  $start_date = trim($_POST["start_date"]);
  $end_date = trim($_POST["end_date"]);

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

  function generateReservationCode($length = 8) {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $code = '';
    for ($i = 0; $i < $length; $i++) {
      $code .= $characters[mt_rand(0, strlen($characters) - 1)];
    }
    return $code;
  }

  do {
    $reservation_code = generateReservationCode();
    $check_sql = "SELECT 1 FROM reservations WHERE reservation_code = '$reservation_code'";
    $check_result = $conn->query($check_sql);
  } while ($check_result && $check_result->num_rows > 0);

  $sql = "INSERT INTO reservations (client_id, reservation_code, country, city, start_date, end_date) 
          VALUES ('$client_id', '$reservation_code', '$country', '$city', '$start_date', '$end_date')";

  if ($conn->query($sql) === FALSE) {
    die("Error inserting reservation: " . $conn->error);
  }

  $conn->close();
}
?>
