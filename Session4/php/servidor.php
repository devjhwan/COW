<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $last_name = trim($_POST["last_name"]);
  $first_name = trim($_POST["first_name"]);
  $doc_type = trim($_POST["doc_type"]);
  $doc_id = strtoupper(trim($_POST["doc_id"]));
  $city = trim($_POST["city"]);

  date_default_timezone_set("UTC");
  $reservation_date = date("Y-m-d H:i:s");

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
          <li class="list-group-item"><strong>City:</strong> <?php echo htmlspecialchars($city); ?></li>
          <li class="list-group-item"><strong>Reservation Date:</strong> <?php echo htmlspecialchars($reservation_date); ?></li>
        </ul>
        <a href="../html/reserve.html" class="btn btn-primary mt-3">Go Back</a>
      </div>
    </div>
  </body>
</html>
<?php
} else {
  header("Location: ../html/reserve.html");
  exit();
}
?>
