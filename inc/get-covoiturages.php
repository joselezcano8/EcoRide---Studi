<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'config.php';

$sql = "SELECT DATE(date) AS date, COUNT(*) AS count FROM covoiturage GROUP BY DATE(date)";
$result = $conn->query($sql);

if (!$result) {
    echo json_encode(['error' => $conn->error]);
    exit();
}

$covoiturages = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $covoiturages[] = [
            'date' => $row['date'],
            'count' => $row['count']
        ];
    }
}

header('Content-Type: application/json');
echo json_encode($covoiturages);

$conn->close();
?>
