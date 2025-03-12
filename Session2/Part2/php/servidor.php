<!--
###############################################################################
#                                                                             #
#   Author: JungHwan Lee                                                      #
#   Submission Deadline: 17th March                                           #
#   Niub: 20467554                                                            #
#                                                                             #
###############################################################################
-->

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "world";

  $conn = new mysqli($servername, $username, $password, $dbname);

  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }

  mysqli_set_charset($conn, "utf8");
  mysqli_query($conn, "SET NAMES 'utf8'");
  mysqli_query($conn, "SET CHARACTER SET 'utf8'");

  $last_name = trim($_POST["last_name"]);
  $first_name = trim($_POST["first_name"]);
  $doc_type = trim($_POST["doc_type"]);
  $doc_id = strtoupper(trim($_POST["doc_id"]));
  $country_name = trim($_POST["country_name"]);
  $country_code = trim($_POST["country_code"]);
  $city = trim($_POST["city"]);

  date_default_timezone_set("UTC");
  $reservation_date = date("Y-m-d H:i:s");

  if (empty($first_name) || strlen($first_name) > 50) {
    header("Location: main.php?error=1");
    exit();
  }

  if (empty($last_name) || strlen($last_name) > 50) {
    header("Location: main.php?error=2");
    exit();
  }

  $validDocTypes = ["DNI", "NIE", "PASSPORT"];
  if (!in_array($doc_type, $validDocTypes)) {
    header("Location: main.php?error=3");
    exit();
  }

  $dniPattern = "/^[0-9]{8}[A-Z]$/";
  $niePattern = "/^[XYZ][0-9]{7}[A-Z]$/";
  $passportPattern = "/^[A-Z0-9]{6,12}$/";

  $isValid = false;

  if ($doc_type === "DNI" && preg_match($dniPattern, $doc_id)) {
    $isValid = true;
  } elseif ($doc_type === "NIE" && preg_match($niePattern, $doc_id)) {
    $isValid = true;
  } elseif ($doc_type === "PASSPORT" && preg_match($passportPattern, $doc_id)) {
    $isValid = true;
  }

  if (!$isValid) {
    header("Location: main.php?error=3");
    exit();
  }

  $sql = "SELECT code FROM countries WHERE name = '$country_name' AND code = '$country_code'";
  $result = mysqli_query($conn, $sql);

  if (!$result || mysqli_num_rows($result) == 0) {
    header("Location: main.php?error=4");
    exit();
  }

  $sql = "SELECT name FROM cities WHERE name = '$city' AND country_code = '$country_code'";
  $result = mysqli_query($conn, $sql);

  if (!$result || mysqli_num_rows($result) == 0) {
    header("Location: main.php?error=4");
    exit();
  }

  mysqli_close($conn);

  include "manage_reservation.php"
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Reservation Details</title>
    <link rel="stylesheet" href="../bootstrap-4.3.1_v2/css/bootstrap.min.css">
  </head>
  <body>
    <div class="container mt-5">
      <div class="card p-4 shadow-lg">
        <h2 class="text-center mb-4">Reservation Details</h2>
        <ul class="list-group">
          <li class="list-group-item"><strong>Last Name:</strong> <?php echo htmlspecialchars($last_name); ?></li>
          <li class="list-group-item"><strong>First Name:</strong> <?php echo htmlspecialchars($first_name); ?></li>
          <li class="list-group-item"><strong>Document Type:</strong> <?php echo htmlspecialchars($doc_type); ?></li>
          <li class="list-group-item"><strong>Document ID:</strong> <?php echo htmlspecialchars($doc_id); ?></li>
          <li class="list-group-item"><strong>Country:</strong> <?php echo htmlspecialchars($country_name); ?></li>
          <li class="list-group-item"><strong>City:</strong> <?php echo htmlspecialchars($city); ?></li>
          <li class="list-group-item"><strong>Reservation Date:</strong> <?php echo htmlspecialchars($reservation_date); ?></li>
        </ul>
        <a href="main.php" class="btn btn-primary mt-3">Go Back</a>
      </div>
    </div>
  </body>
</html>
<?php
} else {
  header("Location: main.php");
  exit();
}
?>
