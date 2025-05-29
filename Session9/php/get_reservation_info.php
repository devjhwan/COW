<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  require_once "db_connection.php";

  header("Content-Type: text/xml; charset=UTF-8");

  $conn = createConnection("hotel");

  if (!$conn) {
    http_response_code(500);
    sendXmlError("Database connection failed.");
  }

  $reservation_code_raw = isset($_POST["reservation_code"]) ? trim($_POST["reservation_code"]) : '';
  $last_name_raw = isset($_POST["last_name"]) ? trim($_POST["last_name"]) : '';

  if (empty($_POST["reservation_code"]) || empty($_POST["last_name"])) {
    http_response_code(400);
    $conn = null;
    sendXmlError("Missing reservation_code or last_name");
    exit;
  }

  $reservation_code = $conn->quote($reservation_code_raw);
  $last_name = $conn->quote($last_name_raw);

  $directory = __DIR__ . "/reservations";
  if (!is_dir($directory)) {
    mkdir($directory, 0755, true);
  }
  
  $filename = $directory . "/reservation_output_" . $reservation_code_raw . ".xml";

  if (file_exists($filename)) {
    try {
      $xmldoc = new DOMDocument();
      libxml_use_internal_errors(true);
  
      if ($xmldoc->load($filename)) {
        echo $xmldoc->saveXML();
        exit;
      }
  
      throw new Exception("Invalid XML format");
  
    } catch (Exception $e) {
      error_log("Invalid cached XML: $filename");
    }
  }

  $sql = "
    SELECT r.reservation_code, r.country, r.city, r.start_date, r.end_date,
      c.first_name, c.last_name, c.doc_type, c.doc_id
    FROM reservations r
    JOIN clients c ON r.client_id = c.client_id
    WHERE r.reservation_code = $reservation_code
    AND c.last_name = $last_name
  ";

  try {
    $result = $conn->query($sql);
    $row = $result->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
      http_response_code(204);
      $conn = null;
      exit;
    }

    $xml = new DOMDocument("1.0", "UTF-8");
    $xml->formatOutput = true;

    $reservation = $xml->createElement("reservation");

    foreach ($row as $key => $value) {
      $node = $xml->createElement($key, $value);
      $reservation->appendChild($node);
    }

    $xml->appendChild($reservation);
    $xml->save($filename);
    echo $xml->saveXML();
  } catch (PDOException $e) {
    http_response_code(500);
    sendXmlError("Query failed.");
  }

  $conn = null;
}

function sendXmlError($message) {
  header("Content-Type: text/xml; charset=UTF-8");
  $xml = new DOMDocument("1.0", "UTF-8");
  $xml->formatOutput = true;

  $error = $xml->createElement("error");
  $msg = $xml->createElement("message", $message);
  $error->appendChild($msg);

  $xml->appendChild($error);
  echo $xml->saveXML();
  exit;
}
?>
