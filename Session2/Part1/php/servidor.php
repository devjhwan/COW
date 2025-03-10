<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $last_name = trim($_POST["last_name"]);
    $first_name = trim($_POST["first_name"]);
    $doc_type = trim($_POST["doc_type"]);
    $doc_id = strtoupper(trim($_POST["doc_id"]));

    if (empty($first_name) || strlen($first_name) > 50) {
        header("Location: cliente.php?error=1&last_name=" . urlencode($last_name) . "&doc_type=" . urlencode($doc_type) . "&doc_id=" . urlencode($doc_id));
        exit();
    }

    if (empty($last_name) || strlen($last_name) > 50) {
        header("Location: cliente.php?error=2&first_name=" . urlencode($first_name) . "&doc_type=" . urlencode($doc_type) . "&doc_id=" . urlencode($doc_id));
        exit();
    }

	$validDocTypes = ["DNI", "NIE", "PASSPORT"];
    if (!in_array($doc_type, $validDocTypes)) {
        header("Location: cliente.php?error=3&last_name=" . urlencode($last_name) . "&first_name=" . urlencode($first_name));
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
        header("Location: cliente.php?error=3&last_name=" . urlencode($last_name) . "&first_name=" . urlencode($first_name) . "&doc_type=" . urlencode($doc_type));
        exit();
    }
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
            </ul>
            <a href="cliente.php" class="btn btn-primary mt-3">Go Back</a>
        </div>
    </div>

    </body>
    </html>

    <?php
} else {
    header("Location: cliente.php");
    exit();
}
?>
