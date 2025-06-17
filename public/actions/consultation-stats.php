<?php
require_once __DIR__ . '/../../app/helpers/db.php';
header('Content-Type: application/json');
$conn = get_db_connection();

// today
$byHour = ["labels" => [], "data" => []];
for ($h = 0; $h < 24; $h++) {
    $byHour["labels"][] = sprintf("%02d:00", $h);
    $byHour["data"][] = 0;
}
$sql = "SELECT HOUR(created_at) as hour, COUNT(*) as total FROM consultations WHERE DATE(created_at) = CURDATE() GROUP BY hour";
$res = $conn->query($sql);
while ($row = $res->fetch_assoc()) {
    $byHour["data"][$row['hour']] = (int)$row['total'];
}

// by weekday
$weekdays = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
$byWeekday = ["labels" => $weekdays, "data" => array_fill(0, 7, 0)];
$sql = "SELECT DAYOFWEEK(created_at) as weekday, COUNT(*) as total FROM consultations WHERE YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1) GROUP BY weekday";
$res = $conn->query($sql);
while ($row = $res->fetch_assoc()) {
    // DAYOFWEEK: 1=Sunday, 2=Monday, ..., 7=Saturday
    $index = $row['weekday'] == 1 ? 6 : $row['weekday'] - 2;
    $byWeekday["data"][$index] = (int)$row['total'];
}

// last 7 days
$byDayOfWeek = ["labels" => [], "data" => []];
for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $byDayOfWeek["labels"][] = $date;
    $byDayOfWeek["data"][] = 0;
}
$sql = "SELECT DATE(created_at) as day, COUNT(*) as total FROM consultations WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 DAY) GROUP BY day";
$res = $conn->query($sql);
while ($row = $res->fetch_assoc()) {
    $idx = array_search($row['day'], $byDayOfWeek['labels']);
    if ($idx !== false) $byDayOfWeek['data'][$idx] = (int)$row['total'];
}

//By month
$months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
$byMonth = ["labels" => $months, "data" => array_fill(0, 12, 0)];
$sql = "SELECT MONTH(created_at) as month, COUNT(*) as total FROM consultations WHERE YEAR(created_at) = YEAR(CURDATE()) GROUP BY month";
$res = $conn->query($sql);
while ($row = $res->fetch_assoc()) {
    $byMonth["data"][$row['month']-1] = (int)$row['total'];
}

$conn->close();
echo json_encode([
    "byHour" => $byHour,
    "byWeekday" => $byWeekday,
    "byDayOfWeek" => $byDayOfWeek,
    "byMonth" => $byMonth
]); 