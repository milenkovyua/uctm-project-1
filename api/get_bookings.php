<?php
require_once('../includes/db_connect.php');

$data = json_decode(file_get_contents('php://input'), true);

try {
    $query = "
        SELECT 
            b.booking_id,
            r.room_number,
            r.room_type,
            b.start_date,
            b.end_date,
            v.full_name as visitor_name
        FROM bookings b
        JOIN rooms r ON b.room_id = r.room_id
        JOIN visitors v ON b.visitor_id = v.visitor_id
        WHERE b.start_date <= :start_date 
        AND b.end_date >= :end_date
    ";
    
    $params = [
        ':start_date' => $data['start_date'],
        ':end_date' => $data['end_date']
    ];

    if (!empty($data['room_number'])) {
        $query .= " AND r.room_number = :room_number";
        $params[':room_number'] = $data['room_number'];
    }

    $query .= " ORDER BY b.start_date";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    header('Content-Type: application/json');
    echo json_encode($bookings);
} catch(PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
}
?>
 