<?php
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['error' => 'Only POST method is allowed']);
  exit;
}

include 'create_hotel_database.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hotel";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  http_response_code(500);
  echo json_encode(['error' => 'Database connection failed']);
  exit;
}
$conn->set_charset("utf8");

$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';
$password_input = isset($_POST['password']) ? $_POST['password'] : '';

if (empty($user_id) || empty($password_input)) {
  http_response_code(400);
  echo json_encode(['error' => 'Missing user_id or password']);
  exit;
}

$user_id_escaped = $conn->real_escape_string($user_id);
$password_escaped = $conn->real_escape_string($password_input);

$sql = "SELECT password FROM users WHERE user_id = '$user_id_escaped'";
$result = $conn->query($sql);

if (!$result) {
  http_response_code(500);
  echo json_encode(['error' => 'Query failed']);
  $conn->close();
  exit;
}

if ($result->num_rows === 0) {
  $insert_sql = "INSERT INTO users (user_id, password) VALUES ('$user_id_escaped', '$password_escaped')";
  if ($conn->query($insert_sql) === TRUE) {
    $_SESSION['user_id'] = $user_id;
    echo json_encode(['success' => true, 'message' => 'New user created and logged in']);
  } else {
    http_response_code(500);
    echo json_encode(['error' => 'User creation failed']);
  }
} else {
  $row = $result->fetch_assoc();
  if ($row['password'] === $password_input) {
    $_SESSION['user_id'] = $user_id;
    echo json_encode(['success' => true, 'message' => 'Login successful']);
  } else {
    http_response_code(401);
    echo json_encode(['error' => 'Incorrect password']);
  }
}

$conn->close();
?>