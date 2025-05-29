<?php
function createConnection($dbname = null, $port = null) {
  checkType($dbname, $port);

  $host = 'localhost';
  $username = 'root';
  $password = '';

  $dsn = "mysql:host=$host";
  if ($port !== null && $port !== '')
    $dsn .= "; port=$port";
  if ($dbname !== null && $dbname !== '')
    $dsn .= "; dbname=$dbname";
  $dsn .= "; charset=utf8";

  try {
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $conn;
  } catch (PDOException $e) {
    return null;
  }
}

function checkType($dbname, $port) {
  if (!is_null($dbname) && !is_string($dbname))
    throw new InvalidArgumentException("dbname must be a string or null");

  if (!is_null($port) && !is_int($port))
    throw new InvalidArgumentException("port must be an integer or null");
}
?>
