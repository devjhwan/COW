<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "db_connection.php";

$cookie_name = "hotelAuth";

if (!isset($_SESSION['user_id']) && isset($_COOKIE[$cookie_name])) {
    parse_str($_COOKIE[$cookie_name], $cookieData);

    $user_id = $cookieData['usr'] ?? '';
    $cookie_hash = $cookieData['hash'] ?? '';

    if ($user_id && $cookie_hash) {
        $conn = createConnection("hotel");
        if ($conn) {
            $user_id_quoted = $conn->quote($user_id);

            $sql = "SELECT password FROM users WHERE user_id = $user_id_quoted";
            $result = $conn->query($sql);
            $row = $result !== false ? $result->fetch(PDO::FETCH_ASSOC) : false;

            if ($row && md5($row['password']) === $cookie_hash) {
                $_SESSION['user_id'] = $user_id;
            }

            $conn = null;
        }
    }
}
?>
