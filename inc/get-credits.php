<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'config.php';

$sql = "SELECT DATE(date_participation) AS date, (COUNT(*) * 2) as credits FROM participants GROUP BY DATE(date)";
$result = $conn->query($sql);

if (!$result) {
    echo json_encode(['error' => $conn->error]);
    exit();
}

$credits = [];
$total_credits = 0;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $total_credits += $row['credits'];
        $credits[] = [
            'date' => $row['date'],
            'total_credits' => $total_credits
        ];
    }
}

header('Content-Type: application/json');
echo json_encode($credits);

$conn->close();
?>
