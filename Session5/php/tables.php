<?php

//Create hotel database if it doesn't exists.
include 'create_hotel_database.php';

$servername = "localhost";
$username = "root";
$password = "";

if (!isset($_GET['database'])) {
    die("Database not selected.");
}

$dbname = $_GET['database'];
$selected_table = isset($_GET['table']) ? $_GET['table'] : "";
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 15;
$offset = ($page - 1) * $limit;


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$tables = [];
$sql = "SHOW TABLES";
$result = $conn->query($sql);

if ($result) {
  while ($row = $result->fetch_array()) {
    $tables[] = $row[0];
  }
}

if (empty($selected_table) && count($tables) > 0) {
  $selected_table = $tables[0];
}

$columns = [];
$rows = [];
$total_rows = 0;
$has_next_page = false;

if (!empty($selected_table)) {
  $sql = "SHOW COLUMNS FROM `$selected_table`";
  $result = $conn->query($sql);
  if ($result) {
    while ($row = $result->fetch_assoc()) {
      $columns[] = $row['Field'];
    }
  }

  $sql = "SELECT * FROM `$selected_table` LIMIT $limit OFFSET $offset";
  $result = $conn->query($sql);
  if ($result) {
    while ($row = $result->fetch_assoc()) {
      $rows[] = $row;
    }
  }

  $sql = "SELECT COUNT(*) AS total FROM `$selected_table`";
  $result = $conn->query($sql);
  if ($result) {
    $total_rows = $result->fetch_assoc()['total'];
  }

  if (($offset + $limit) < $total_rows) {
    $has_next_page = true;
  }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Tables in <?php echo htmlspecialchars($dbname); ?></title>
    <link rel="stylesheet" href="../bootstrap-4.3.1_v2/css/bootstrap.min.css">
  </head>
  <body>

  <div class="container mt-5">
    <h2 class="text-center">Database: <strong><?php echo htmlspecialchars($dbname); ?></strong></h2>

    <ul class="nav nav-tabs mt-4">
      <?php foreach ($tables as $table): ?>
        <li class="nav-item">
          <a class="nav-link <?php echo ($table == $selected_table) ? 'active' : ''; ?>" 
          href="tables.php?database=<?php echo urlencode($dbname); ?>&table=<?php echo urlencode($table); ?>&page=1">
            <?php echo htmlspecialchars($table); ?>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>

    <div class="card p-4 shadow-lg mt-4">
      <h4 class="text-center mb-4">Table: <strong><?php echo htmlspecialchars($selected_table); ?></strong></h4>
      
      <?php if (!empty($columns)): ?>
        <div class="table-responsive">
          <table class="table table-sm table-bordered">
            <thead class="thead-dark">
              <tr>
                <?php foreach ($columns as $column): ?>
                  <th><?php echo htmlspecialchars($column); ?></th>
                <?php endforeach; ?>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($rows)): ?>
                <?php foreach ($rows as $row): ?>
                  <tr>
                    <?php foreach ($columns as $column): ?>
                      <td><?php echo htmlspecialchars($row[$column]); ?></td>
                    <?php endforeach; ?>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="<?php echo count($columns); ?>" class="text-center">No data available</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <div class="d-flex justify-content-between mt-3">
      <div>
        <a href="tables.php?database=<?php echo urlencode($dbname); ?>&table=<?php echo urlencode($selected_table); ?>&page=<?php echo max(1, $page - 1); ?>"
        class="btn btn-outline-primary <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
          ← Previous
        </a>

        <a href="tables.php?database=<?php echo urlencode($dbname); ?>&table=<?php echo urlencode($selected_table); ?>&page=<?php echo $page + 1; ?>"
        class="btn btn-outline-primary <?php echo (!$has_next_page) ? 'disabled' : ''; ?>">
          Next →
        </a>
      </div>

      <a href="../html/database.html" class="btn btn-secondary">Go Back</a>
    </div>

      <?php else: ?>
        <p class="text-center text-danger">No columns found in this table.</p>
      <?php endif; ?>
    </div>
  </div>

  </body>
</html>
