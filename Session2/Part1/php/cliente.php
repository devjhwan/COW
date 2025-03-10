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

        <?php
        $error_msg = "";
        $last_name = isset($_GET['last_name']) ? htmlspecialchars($_GET['last_name']) : "";
        $first_name = isset($_GET['first_name']) ? htmlspecialchars($_GET['first_name']) : "";
        $doc_type = isset($_GET['doc_type']) ? htmlspecialchars($_GET['doc_type']) : "";
        $doc_id = isset($_GET['doc_id']) ? htmlspecialchars($_GET['doc_id']) : "";

        if (isset($_GET['error'])) {
            $error_code = $_GET['error'];
            if ($error_code == "1") {
                $error_msg = "First Name is required.";
            } elseif ($error_code == "2") {
                $error_msg = "Last Name is required.";
            } elseif ($error_code == "3") {
                $error_msg = "Invalid Document ID format.";
            }
            echo '<div class="alert alert-danger text-center">' . $error_msg . '</div>';
        }
        ?>

        <form action="servidor.php" method="post">
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $last_name; ?>" required>
            </div>

            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $first_name; ?>" required>
            </div>

            <div class="form-group">
                <label for="doc_type">Document Type</label>
                <select class="form-control" id="doc_type" name="doc_type" required>
                    <option value="DNI" <?php if ($doc_type == "DNI") echo "selected"; ?>>DNI</option>
                    <option value="NIE" <?php if ($doc_type == "NIE") echo "selected"; ?>>NIE</option>
                    <option value="PASSPORT" <?php if ($doc_type == "PASSPORT") echo "selected"; ?>>Passport</option>
                </select>
            </div>

            <div class="form-group">
                <label for="doc_id">Document ID</label>
                <input type="text" class="form-control" id="doc_id" name="doc_id" value="<?php echo $doc_id; ?>" required>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Reserve</button>
            <a href="../index.html" class="btn btn-secondary btn-block mt-2">Go Back</a>
        </form>
    </div>
</div>

</body>
</html>
