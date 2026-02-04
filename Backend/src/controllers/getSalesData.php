<?php
include(__DIR__ . '/dbConnection.php');

$type = $_GET['type'] ?? 'monthly';

$labels = [];
$data = [];

switch ($type) {

    case 'daily':
        $sql = "
            SELECT DATE(created) as label, SUM(price * quantity) as total
            FROM order_list
            WHERE DATE(created) = CURDATE()
            GROUP BY DATE(created)
        ";
        break;

    case 'weekly':
        $sql = "
            SELECT DATE(created) as label, SUM(price * quantity) as total
            FROM order_list
            WHERE created >= CURDATE() - INTERVAL 6 DAY
            GROUP BY DATE(created)
            ORDER BY created
        ";
        break;

    case 'yearly':
        $sql = "
            SELECT YEAR(created) as label, SUM(price * quantity) as total
            FROM order_list
            GROUP BY YEAR(created)
            ORDER BY YEAR(created)
        ";
        break;

    default: 
        $sql = "
            SELECT DATE_FORMAT(created, '%b') as label, SUM(price * quantity) as total
            FROM order_list
            WHERE YEAR(created) = YEAR(CURDATE())
            GROUP BY MONTH(created)
            ORDER BY MONTH(created)
        ";
}

$result = mysqli_query($con, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    $labels[] = $row['label'];
    $data[] = (float) $row['total'];
}

echo json_encode([
    'labels' => $labels,
    'data' => $data
]);