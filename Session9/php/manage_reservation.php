<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  include_once 'create_hotel_database.php';
  require_once "db_connection.php";

  $conn = createConnection("hotel");
  if ($conn === null)
    die("Connection failed");

  session_start();
  $user_id_value = 'NULL';

  if (isset($_SESSION['user_id'])) {
    $session_user_id = addslashes($_SESSION['user_id']);
    $result = $conn->query("SELECT id FROM users WHERE user_id = '$session_user_id'");
    $row = $result !== false ? $result->fetch(PDO::FETCH_ASSOC) : false;
    if ($row && isset($row['id']))
      $user_id_value = (int)$row['id'];
  }

  $first_name = addslashes(trim($_POST["first_name"]));
  $last_name = addslashes(trim($_POST["last_name"]));
  $doc_type = addslashes(trim($_POST["doc_type"]));
  $doc_id = addslashes(strtoupper(trim($_POST["doc_id"])));
  $city = addslashes(trim($_POST["city"]));
  $country = addslashes(trim($_POST["country"]));
  $start_date = addslashes(trim($_POST["start_date"]));
  $end_date = addslashes(trim($_POST["end_date"]));

  if ($user_id_value === 'NULL') {
    $sql = "SELECT client_id FROM clients 
        WHERE doc_type = '$doc_type' AND doc_id = '$doc_id' AND user_id IS NULL";
  } else {
    $sql = "SELECT client_id FROM clients 
        WHERE doc_type = '$doc_type' AND doc_id = '$doc_id' AND user_id = $user_id_value";
  }

  $result = $conn->query($sql);
  $client_id = null;

  if ($result !== false && ($row = $result->fetch(PDO::FETCH_ASSOC)))
    $client_id = $row["client_id"];
  else {
    if ($user_id_value === 'NULL') {
      $sql = "INSERT INTO clients (first_name, last_name, doc_type, doc_id, user_id)
          VALUES ('$first_name', '$last_name', '$doc_type', '$doc_id', NULL)";
    } else {
      $sql = "INSERT INTO clients (first_name, last_name, doc_type, doc_id, user_id)
          VALUES ('$first_name', '$last_name', '$doc_type', '$doc_id', $user_id_value)";
    }

    if ($conn->exec($sql) === false) 
      die("Error inserting client.");

    $client_id = $conn->lastInsertId();
  }

  function generateReservationCode($length = 8) {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $code = '';
    for ($i = 0; $i < $length; $i++)
      $code .= $characters[mt_rand(0, strlen($characters) - 1)];
    return $code;
  }

  do {
    $reservation_code = generateReservationCode();
    $check_sql = "SELECT 1 FROM reservations WHERE reservation_code = '$reservation_code'";
    $check_result = $conn->query($check_sql);
  } while ($check_result && $check_result->fetch(PDO::FETCH_NUM));

  $sql = "INSERT INTO reservations (client_id, reservation_code, country, city, start_date, end_date)
      VALUES ('$client_id', '$reservation_code', '$country', '$city', '$start_date', '$end_date')";

  if ($conn->exec($sql) === false) 
    die("Error inserting reservation.");

  $conn = null;
}
?>
