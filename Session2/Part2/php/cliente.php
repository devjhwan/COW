<?php
session_start();

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

$countries = [];
$sql = "SELECT name, code FROM countries WHERE name IS NOT NULL AND name <> '' ORDER BY name ASC";
$result = mysqli_query($conn, $sql);
if ($result) {
  while ($row = mysqli_fetch_assoc($result)) {
    $countries[] = $row;
  }
}

$error_code = isset($_GET['error']) ? $_GET['error'] : "";
$error_msg = "";

if ($error_code == "1") {
  $error_msg = "Error: First Name is invalid.";
} elseif ($error_code == "2") {
  $error_msg = "Error: Last Name is invalid.";
} elseif ($error_code == "3") {
  $error_msg = "Error: Invalid Document ID format.";
} elseif ($error_code == "4") {
  $error_msg = "Error: Country or City does not exist in the database.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['country_form'])) {
  $_SESSION['form_data'] = $_POST;

  if (!empty($_POST['country'])) {
    list($selected_country, $selected_country_code) = explode('|', $_POST['country']);
    $_SESSION['form_data']['country_name'] = $selected_country;
    $_SESSION['form_data']['country_code'] = $selected_country_code;

    header("Location: main.php?country=" . urlencode($selected_country) . "&code=" . urlencode($selected_country_code));
    exit();
  }
}

$form_data = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];

$selected_country = isset($_GET['country']) ? $_GET['country'] : (isset($form_data['country_name']) ? $form_data['country_name'] : "");
$selected_country_code = isset($_GET['code']) ? $_GET['code'] : (isset($form_data['country_code']) ? $form_data['country_code'] : "");

$cities = [];
if (!empty($selected_country_code)) {
  $sql = "SELECT name FROM cities WHERE country_code = '$selected_country_code'";
  $result = mysqli_query($conn, $sql);
  while ($row = mysqli_fetch_assoc($result)) {
    $cities[] = $row['name'];
  }
}

$selected_city = isset($form_data['city']) ? $form_data['city'] : "";

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Reservation Form</title>
    <link rel="stylesheet" href="../bootstrap-4.3.1_v2/css/bootstrap.min.css">
  </head>
  <body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
      <div class="card p-4 shadow-lg" style="max-width: 400px; width: 100%;">
        <h2 class="text-center mb-4">Reservation Form</h2>
        <?php if (!empty($error_msg)): ?>
          <div class="alert alert-danger text-center"><?php echo $error_msg; ?></div>
        <?php endif; ?>
        <form action="cliente.php" method="post">
          <input type="hidden" name="country_form" value="1">
          <div class="form-group">
            <label for="country">Country</label>
            <select class="form-control" id="country" name="country" required onchange="this.form.submit()">
              <option value="">-</option>
              <?php foreach ($countries as $country): ?>
                <option value="<?php echo htmlspecialchars($country['name']) . '|' . htmlspecialchars($country['code']); ?>"
                  <?php if ($selected_country == $country['name']) echo "selected"; ?>>
                  <?php echo htmlspecialchars($country['name']); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        </form>
        <form action="servidor.php" method="post">
          <input type="hidden" name="country_name" value="<?php echo htmlspecialchars($selected_country); ?>">
          <input type="hidden" name="country_code" value="<?php echo htmlspecialchars($selected_country_code); ?>">

          <div class="form-group">
            <label for="last_name">Last Name</label>
            <input type="text" class="form-control" id="last_name" name="last_name"
              value="<?php echo isset($form_data['last_name']) ? htmlspecialchars($form_data['last_name']) : ''; ?>" required>
          </div>
          <div class="form-group">
            <label for="first_name">First Name</label>
            <input type="text" class="form-control" id="first_name" name="first_name"
              value="<?php echo isset($form_data['first_name']) ? htmlspecialchars($form_data['first_name']) : ''; ?>" required>
          </div>
          <div class="form-group">
            <label for="doc_type">Document Type</label>
            <select class="form-control" id="doc_type" name="doc_type" required>
              <option value="DNI" <?php if (isset($form_data['doc_type']) && $form_data['doc_type'] == "DNI") echo "selected"; ?>>DNI</option>
              <option value="NIE" <?php if (isset($form_data['doc_type']) && $form_data['doc_type'] == "NIE") echo "selected"; ?>>NIE</option>
              <option value="PASSPORT" <?php if (isset($form_data['doc_type']) && $form_data['doc_type'] == "PASSPORT") echo "selected"; ?>>Passport</option>
            </select>
          </div>
          <div class="form-group">
            <label for="doc_id">Document ID</label>
            <input type="text" class="form-control" id="doc_id" name="doc_id"
              value="<?php echo isset($form_data['doc_id']) ? htmlspecialchars($form_data['doc_id']) : ''; ?>" required>
          </div>

          <div class="form-group">
            <label for="city">City</label>
            <select class="form-control" id="city" name="city" required>
              <option value="">-</option>
              <?php foreach ($cities as $city): ?>
                <option value="<?php echo htmlspecialchars($city); ?>"
                  <?php if ($selected_city == $city) echo "selected"; ?>>
                  <?php echo htmlspecialchars($city); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <button type="submit" class="btn btn-primary btn-block">Reserve</button>
          <a href="../index.html" class="btn btn-secondary btn-block mt-2">Go Back</a>
        </form>
      </div>
    </div>
  </body>
</html>
