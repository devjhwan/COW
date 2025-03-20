<!--
###############################################################################
#                                                                             #
#   Author: JungHwan Lee                                                      #
#   Submission Deadline: 31th March                                           #
#   Niub: 20467554                                                            #
#                                                                             #
###############################################################################
-->

<?php
header("Content-Type: application/javascript; charset=UTF-8");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "world";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo 'insertCities("[]");';
    exit();
}

$conn->set_charset("utf8");

$country_code = isset($_GET['country_code']) ? $_GET['country_code'] : "";

if (empty($country_code)) {
    echo 'insertCities("[]");';
    exit();
}

$country_code = $conn->real_escape_string($country_code);
$sql = "SELECT name FROM cities WHERE country_code = '$country_code'";
$result = $conn->query($sql);

$cities = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $cities[] = $row['name'];
    }
}

echo 'insertCities(\'' . json_encode($cities) . '\');';

$conn->close();
?>
