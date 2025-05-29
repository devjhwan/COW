<?php
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['error' => 'Only POST method is allowed']);
  exit;
}

include_once 'create_hotel_database.php';
require_once "db_connection.php";

$conn = createConnection("hotel");
if ($conn === null) {
  http_response_code(500);
  echo json_encode(['error' => 'Database connection failed']);
  exit;
}

$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';
$password_input = isset($_POST['password']) ? $_POST['password'] : '';

if ($user_id === '' || $password_input === '') {
  http_response_code(400);
  echo json_encode(['error' => 'Missing user_id or password']);
  $conn = null;
  exit;
}

$user_id_escaped = addslashes($user_id);
$password_escaped = addslashes($password_input);

$sql = "SELECT password FROM users WHERE user_id = '$user_id_escaped'";
try {
  $result = $conn->query($sql);
  $row = $result !== false ? $result->fetch(PDO::FETCH_ASSOC) : false;

  if (!$row) {
    $insert_sql = "INSERT INTO users (user_id, password) VALUES ('$user_id_escaped', '$password_escaped')";
    if ($conn->exec($insert_sql) !== false) {
      $_SESSION['user_id'] = $user_id;
      echo json_encode(['success' => true, 'message' => 'New user created and logged in']);
    } else {
      http_response_code(500);
      echo json_encode(['error' => 'User creation failed']);
    }
  } else {
    if ($row['password'] === $password_input) {
      $_SESSION['user_id'] = $user_id;
      echo json_encode(['success' => true, 'message' => 'Login successful']);
    } else {
      http_response_code(401);
      echo json_encode(['error' => 'Incorrect password']);
    }
  }
} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode(['error' => 'Query failed']);
}

$conn = null;
?>
