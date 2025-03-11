<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Reservation Layout</title>
    <link rel="stylesheet" href="../bootstrap-4.3.1_v2/css/bootstrap.min.css">
  </head>
  <body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
      <div class="row w-100">
        <div class="col-md-6 d-flex justify-content-center">
          <?php 
            $selected_country = isset($_GET['country']) ? $_GET['country'] : ""; 
            include 'cliente.php'; 
          ?>
        </div>
        
        <div class="col-md-6 d-flex justify-content-center">
          <?php include 'database.php'; ?>
        </div>
      </div>
    </div>
  </body>
</html>
